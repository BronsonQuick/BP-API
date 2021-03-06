<?php

/**
 * Access Activity.
 *
 * Class BP_REST_Activity_Controller
 */
class BP_REST_Activity_Controller {

	/**
	 * Add our two activity endpoints.
	 */
	public function register_routes() {

		register_rest_route( 'bp/v1', '/activity', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_items' ),
			),
			'schema' => array( $this, 'get_item_schema' ),
		) );

		register_rest_route( 'bp/v1', '/activity/(?P<id>\d+)', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_item' ),
			),
			'schema' => array( $this, 'get_item_schema' ),
		) );

	}

	/**
	 * Get the activity schema conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'activity',
			'type'       => 'object',
			/*
			 * Base properties for each Activity
			 */
			'properties' => array(
				'activity_id' => array(
					'description' => __( 'Unique identifier for the activity.', 'bp-rest-api' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'activity_username' => array(
					'description' => __( 'Unique username for the activity.', 'bp-rest-api' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'user_id' => array(
					'description' => __( 'Unique identifier for the user.', 'bp-rest-api' ),
					'type'        => 'int',
					'context'     => array( 'view' ),
				),
				'avatar' => array(
					'description' => __( 'The Avatar URL.', 'bp-rest-api' ),
					'type'        => 'uri',
					'context'     => array( 'view' ),
				),
				'action' => array(
					'description' => __( 'The action that took place in HTML format.', 'bp-rest-api' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'content' => array(
					'description' => __( 'The content of the activity.', 'bp-rest-api' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'comment_count' => array(
					'description' => __( 'The number of comments on the activity.', 'bp-rest-api' ),
					'type'        => 'integer',
					'context'     => array( 'view' ),
				),
				'can_comment' => array(
					'description' => __( 'A boolean value of whether the logged in user can comment on the activity.', 'bp-rest-api' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
				'can_favorite' => array(
					'description' => __( 'A boolean value of whether the logged in user can favourite the activity.', 'bp-rest-api' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
				'is_favorite' => array(
					'description' => __( 'A boolean value of whether the logged in user has favourited the activity.', 'bp-rest-api' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
				'can_delete' => array(
					'description' => __( 'A boolean value about whether the logged in user can delete the activity.', 'bp-rest-api' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
			),
		);

		return $schema;
	}

	/**
	 * Get a single activity.
	 * @param $request
	 *
	 * @return object|WP_Error
	 */
	public function get_item( $request ) {

		$id = (int) absint( $request['id'] );

		if ( empty( $id ) ) {
			return new WP_Error( 'rest_activity_invalid_id', __( 'Invalid activity id.', 'bp-rest-api' ), array( 'status' => 404 ) );
		}

		$response = $this->get_activity( array( 'in' => (int) $id ) );

		return (object) $response;
	}

	/**
	 * Get items.
	 * @param array $filter
	 *
	 * @return mixed
	 */
	public function get_items( $filter = array() ) {
		$response = $this->get_activity( $filter['filter'] );
		return (object) $response;
	}

	/**
	 * Get the BuddyPress activity.
	 * @param $filter
	 *
	 * @return mixed|WP_Error|WP_REST_Response
	 */
	public function get_activity( $filter ) {

		$args = $filter;

		if ( bp_has_activities( $args ) ) {

			while ( bp_activities() ) {

				bp_the_activity();

				$activity = array(
					'avatar'            => bp_core_fetch_avatar( array( 'html' => false, 'item_id' => bp_get_activity_id() ) ),
					'action'            => bp_get_activity_action(),
					'content'           => bp_get_activity_content_body(),
					'activity_id'       => bp_get_activity_id(),
					'activity_username' => bp_core_get_username( bp_get_activity_user_id() ),
					'user_id'           => bp_get_activity_user_id(),
					'comment_count'     => bp_activity_get_comment_count(),
					'can_comment'       => bp_activity_can_comment(),
					'can_favorite'      => bp_activity_can_favorite(),
					'is_favorite'       => bp_get_activity_is_favorite(),
					'can_delete'        => bp_activity_user_can_delete(),
				);

				$activity = apply_filters( 'bp_rest_prepare_activity', $activity );

				$activities[] = $activity;

			}

			$data = $activities;

			$data = apply_filters( 'bp_rest_prepare_activities', $data );

		} else {
			return new WP_Error( 'bp_rest_activity', __( 'No Activity Found.', 'bp-rest-api' ), array( 'status' => 200 ) );
		}

		$response = new WP_REST_Response();
		$response->set_data( $data );
		$response = rest_ensure_response( $response );

		return (object) $response;

	}
}

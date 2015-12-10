<?php
class BP_REST_Activity_Controller {

	public function register_routes() {
		register_rest_route( 'bp/v1', '/activity', array(
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'get_items' ),
			),
			'schema' => array( $this, 'get_item_schema' ),
		) );
	}

	/**
	 * Get the activity schema conforming to JSON Schema
	 *
	 * @return array
	 */
	public function get_item_schema(){
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'activity',
			'type'       => 'object',
			/*
			 * Base properties for each Activity
			 */
			'properties' => array(
				'activity_id' => array(
					'description' => 'Unique identifier for the activity.',
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'activity_username' => array(
					'description' => 'Unique username for the activity',
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'user_id' => array(
					'description' => 'Unique identifier for the user',
					'type'        => 'int',
					'context'     => array( 'view' ),
				),
				'avatar' => array(
					'description' => 'The Avatar URL',
					'type'        => 'uri',
					'context'     => array( 'view' ),
				),
				'action' => array(
					'description' => 'The action that took place in HTML format',
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'content' => array(
					'description' => 'The content got the activity',
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'comment_count' => array(
					'description' => 'The number of comments on the activity',
					'type'        => 'integer',
					'context'     => array( 'view' ),
				),
				'can_comment' => array(
					'description' => 'A boolean value of whether the logged in user can comment on the activity',
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
				'can_favorite' => array(
					'description' => 'A boolean value of whether the logged in user can favorite the activity',
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
				'is_favorite' => array(
					'description' => 'A boolean value of whether the logged in user has favorited the activity',
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
				'can_delete' => array(
					'description' => 'A boolean value about whether the logged in user can delete the activity',
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),
			),
		);
		return $schema;
	}
}

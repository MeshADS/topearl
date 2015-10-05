<?php
	return  [

			"formElements" => [
				"checkbox" => [
								"rules" => [
									"position"=>"required|min:1",
									"list" => "required",
									"value" => "required"
								],
								"messages" => [
									"position.required"=>"The position field is required.",
									"position.min"=>"Minimum position value is 1.",
									"list.required" => "No list created.",
									"value.required" => "No value for list."
								]
				],
				"radio-button" => [
								"rules" => [
									"position"=>"required|min:1",
									"list" => "required",
									"value" => "required"
								],
								"messages" => [
									"position.required"=>"The position field is required.",
									"position.min"=>"Minimum position value is 1.",
									"list.required" => "No list created.",
									"value.required" => "No value for list."
								]
				],
				"select" => [
							"rules" => [
								"position"=>"required|min:1",
								"list" => "required",
								"value" => "required"
							],
							"messages" => [
								"position.required"=>"The position field is required.",
								"position.min"=>"Minimum position value is 1.",
								"list.required" => "No list created.",
								"value.required" => "No value for list."
							]
				],
				"text-input" => [
								"rules" => [
									"name"=>"required",
								],
								"messages" => [
									"name.required"=>"The name field is required."
								]
				],
				"textarea" => [
								"rules" => [
									"name"=>"required"
								],
								"messages" => [
									"name.required"=>"The name field is required."
								]
				],
			],
			"updateFormElements" => [
				"checkbox" => [
								"rules" => [
									"position"=>"required|min:1"
								],
								"messages" => [
									"position.required"=>"The position field is required.",
									"position.min"=>"Minimum position value is 1."
								]
				],
				"radio-button" => [
								"rules" => [
									"position"=>"required|min:1"
								],
								"messages" => [
									"position.required"=>"The position field is required.",
									"position.min"=>"Minimum position value is 1."
								]
				],
				"select" => [
							"rules" => [
								"position"=>"required|min:1"
							],
							"messages" => [
								"position.required"=>"The position field is required.",
								"position.min"=>"Minimum position value is 1."
							]
				],
				"text-input" => [
								"rules" => [
									"name"=>"required",
								],
								"messages" => [
									"name.required"=>"The name field is required."
								]
				],
				"textarea" => [
								"rules" => [
									"name"=>"required"
								],
								"messages" => [
									"name.required"=>"The name field is required."
								]
				],
			]
		];
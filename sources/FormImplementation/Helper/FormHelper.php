<?php

namespace Combodo\iTop\FormImplementation\Helper;

use Combodo\iTop\FormImplementation\Dto\CountDto;
use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use Combodo\iTop\FormSDK\Service\FormFactory;
use Combodo\iTop\FormSDK\Service\FormManager;
use DateTime;
use MetaModel;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class FormHelper
{

	static private array $MODES_DEFINITIONS = [
		0 => [
			'group' => false,
			'layout' => false,
			'object_only' => false,
			'object_count' => 5
		],
		1 => [
			'group' => true,
			'layout' => false,
			'object_only' => false,
			'object_count' => 5
		],
		2 => [
			'group' => true,
			'layout' => true,
			'object_only' => false,
			'object_count' => 5
		],
		3 => [
			'group' => true,
			'layout' => false,
			'object_only' => true,
			'object_count' => 1
		]
	];

	/**
	 * Create a sample form factory for demo purpose.
	 *
	 * @param \Combodo\iTop\FormSDK\Service\FormManager $oFormManager
	 * @param \Symfony\Component\Routing\RouterInterface $oRouter
	 * @param int $iMode
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Exception
	 */
	static public function CreateSampleFormFactory(FormManager $oFormManager, RouterInterface $oRouter, int $iMode) : FormFactory
	{
		// create a factory
		$oFormFactory = $oFormManager->CreateFactory();

		// form data
		$aData = [
			'city' => 'Autun',
			'tel' => '+33(6) 35 57 48 77',
			'birthday' => new DateTime('1979/06/27'),
			'count' => 10,
			'counts' =>  ['count1' => 10, 'count2' => 20, 'count3' => 30],
			'counts2' => new CountDto(),
			'interval' => ['days' => '12', 'hours' => '13', 'years' => '10', 'months' => '6', 'minutes' => '0', 'seconds' => '0', 'weeks' => '3'],
			'blog' => '<h2>Your <b>story</b></h2><br><b>start here</b>, bla bla bla bla bla bla<br>bla bla bla bla bla bla<br>bla bla bla bla bla bla<br>bla bla bla bla bla bla',
			'notify' => true,
			'language' => 'FR FR',
			'mode' => '1',
			'options' => ['0', '2', '4'],
			'collection' => [
				[
					'text1' => 'Benjamin',
					'text2' => 'DALSASS',
					'date1' => new DateTime('1979/06/27')
				],
				[
					'text1' => 'Nelly',
					'text2' => 'DALSASS',
					'date1' => new DateTime('1977/04/6')
				],
				[
					'text1' => 'Léonard',
					'text2' => 'BASTID',
					'date1' => new DateTime('1975/03/16')
				]
			]
		];
		$oFormFactory->SetData($aData);

		// add X person forms...
		for($i = 0 ; $i < self::$MODES_DEFINITIONS[$iMode]['object_count'] ; $i++){

			// retrieve person
			$oPerson = MetaModel::GetObject('Person', $i+1);

			// create object adapter
			$oObjectPlugin = $oFormFactory->CreateObjectAdapter($oPerson, self::$MODES_DEFINITIONS[$iMode]['group']);
			$oObjectPlugin->AddAttribute('name');
			$oObjectPlugin->AddAttribute('mobile_phone');
		}

		if(!self::$MODES_DEFINITIONS[$iMode]['object_only']) {

			// city - text
			$oFormFactory->AddTextField('city', [
				'label'       => 'Ma ville',
				'help'        => 'This is where you live',
				'constraints' => new Length(['min' => 3])
			]);

			// tel - text with pattern
			$oFormFactory->AddTextField('tel', [
				'label'       => 'Tel',
				'constraints' => new Regex(['pattern' => '/\+33\(\d\) \d\d \d\d \d\d \d\d/'], null, '+{33}(0) 00 00 00 00'),
				'required'    => false
			]);

			// birthday - date
			$oFormFactory->AddDateField('birthday', [
				'label'    => 'Anniversaire',
				'widget'   => 'single_text',
				'required' => false
			]);

			// count - number
			$oFormFactory->AddNumberField('count', [
				'label'    => 'Compteur',
				'required' => false
			]);

			// counts - fieldset
			$oCount1 = new FormFieldDescription('count1', FormFieldTypeEnumeration::NUMBER, []);
			$oCount2 = new FormFieldDescription('count2', FormFieldTypeEnumeration::NUMBER, []);
			$oCount3 = new FormFieldDescription('count3', FormFieldTypeEnumeration::NUMBER, []);
			$oFormFactory->AddFieldSet('counts', [
				'label'    => 'Compteurs (from array)',
				'required' => false,
				'fields'   => [
					$oCount1,
					$oCount2,
					$oCount3
				]
			]);

			// counts - fieldset alternative
			$oCount1 = new FormFieldDescription('count1', FormFieldTypeEnumeration::NUMBER, []);
			$oCount2 = new FormFieldDescription('count2', FormFieldTypeEnumeration::NUMBER, []);
			$oCount3 = new FormFieldDescription('count3', FormFieldTypeEnumeration::NUMBER, []);
			$oFormFactory->AddFieldSet('counts2', [
				'label'    => 'Compteurs (from object) need array access implementation !!!',
				'label_attr' => [
					'class' => 'error-label'
				],
				'required' => false,
				'fields'   => [
					$oCount1,
					$oCount2,
					$oCount3 // OR $oData
				]
			]);

			// interval - duration
			$oFormFactory->AddDurationField('interval', [
				'label'        => 'Fréquence',
				'with_minutes' => true,
				'with_seconds' => true,
				'with_weeks'   => true,
				'with_days'    => false,
				'attr'         => [
					'class' => 'form_interval_horizontal'
				]
			]);

			// ready
			$oFormFactory->AddSwitchField('notify', [
				'label' => 'Veuillez m\'avertir en cas de changement',
			]);

			// blog - date
			$oFormFactory->AddAreaField('blog', [
				'label'    => 'Blog',
				'required' => false
			]);

			// language - select with static data
			$oFormFactory->AddSelectField('language', [
				'label'   => 'Ma langue',
				'choices' => SelectDataProvider::GetApplicationLanguages()
			]);

			// dog - select with ajax API
			$oFormFactory->AddSelectAjaxField('dog', [
				'label'       => 'Mon Chien',
				'placeholder' => 'Sélectionnez un chien',
				'required'    => false,

			], [
				'url'             => 'http://localhost'.$oRouter->generate('formSDK_ajax_select'),
				'query_parameter' => 'query',
				'value_field'     => 'breed',
				'label_field'     => 'breed',
				'search_field'    => 'breed',
				'threshold'       => 20,
				'max_items'       => 1
			]);

			// friends - select with  OQL
			$oFormFactory->AddSelectOqlField('friends', [
				'label'    => 'Ma personne',
				'required' => false
			], 'Person', 'SELECT Person', [], '', 20);

			// requests - select with  OQL
			$oFormFactory->AddSelectOqlField('requests', [
				'label'    => 'Tickets',
				'required' => false
			], 'UserRequest', 'SELECT UserRequest', [], '', 20);

			// mode - select with static data
			$oFormFactory->AddSelectField('mode', [
				'label'      => 'Mon mode',
				'choices'    => SelectDataProvider::GetModes(),
				'expanded'   => true,
				'multiple'   => false,
				'label_attr' => [
					'class' => 'radio-inline'
				]
			]);

			// options - select with static data
			$oFormFactory->AddSelectField('options', [
				'label'      => 'Mes options',
				'choices'    => SelectDataProvider::GetOptions(),
				'expanded'   => true,
				'multiple'   => true,
				'label_attr' => [
					'class' => 'checkbox-inline'
				]
			]);

			// options - select with static data
			$oText1 = new FormFieldDescription('text1', FormFieldTypeEnumeration::TEXT, []);
			$oText2 = new FormFieldDescription('text2', FormFieldTypeEnumeration::TEXT, []);
			$oDate = new FormFieldDescription('date1', FormFieldTypeEnumeration::DATE, [
				'widget'   => 'single_text'
			]);
			$oFormFactory->AddCollectionField('collection', [
				'label'      => 'Une Collection',
				'element_type'    => FormFieldTypeEnumeration::FIELDSET,
				'fields_labels' => ['Prénom', 'Nom', 'Naissance'],
				'element_options' => [
					'fields' => [
						'text1' => $oText1,
						'text2' => $oText2,
						'date1' => $oDate]
				]
			]);

			// file - file download
			$oFormFactory->AddFileField('file', [
				'label' => 'Download a file...',
				'required' => false
			]);

		}

		if(self::$MODES_DEFINITIONS[$iMode]['layout']){

			$aDescription = [

				':row_1' => [
					'@rank' => 2,
					':column_1' => [
						'@css_classes' => ['custom-container', 'container-flower', 'layout-grow'],
						'@rank' => 2,
						':fieldset_1' => [
							'@label' => 'Informations Utilisateur',
							'@rank' => 2,
							'birthday' => [
								'@rank' => 3,
							],
							'city' => [
								'@rank' => 0,
							],
							'tel' => [
								'@rank' => 1,
							],
						],
					],
					':column_2' => [
						'@css_classes' => ['custom-container', 'container-color', 'mb-3'],
						'@rank' => 1,
						':fieldset_1' => [
							'@label' => 'Options',
							'@rank' => 1,
							'mode',
							'interval'
						],
					],

				],
				':row_2' => [
					'@rank' => 1,
					'@css_classes' => ['custom-container', 'container-color2', 'mb-3'],
					':column_1' => [
						'@css_classes' => ['flex-grow-1'],
						':fieldset_1' => [
							'@rank' => 2,
							'file'
						],
					],
				]
			];

			if(self::$MODES_DEFINITIONS[$iMode]['group']){
				$aDescription[':row_1'][':column_2'][':fieldset_2'][] = 'Person_2';
				$aDescription[':row_2'][':column_1'][':fieldset_2'] = [
					'@rank' => 1, 'Person_1', 'Person_3', 'notify'];
			}
			else{
				$aDescription[':row_2'][':column_1'][':fieldset_2'] = [ '@rank' => 1, 'Person_1_name'];
			}

			// layout description
			$oFormFactory->SetLayoutDescription($aDescription);
		}

		return $oFormFactory;
	}



//	private function Definitions()
//	{
//		// condensé //  + lisible - organisé // generic id
//		$aDescription = [
//
//			'row__1' => [
//				'column__1' => [
//					'@label' => 'Informations',
//					'@css_classes' => 'custom-container container-flower layout-grow',
//					'fieldset__1' => [ 'birthday', 'city', 'tel'],
//				],
//				'column__2' => [
//					'@label' => 'Options',
//					'@css_classes' => 'custom-container container-color mb-3',
//					'fieldset__1' => ['mode', 'interval'],
//				],
//
//			],
//			'row__2' => [
//				'@label' => 'Telechargement',
//				'@css_classes' => 'custom-container container-color2 mb-3',
//				'fieldset__2' => ['file'],
//			]
//		];
//
//		// condensé //  + lisible - organisé // generic id
//		$aDescription2 = [
//
//			'+row__#1' => [
//				'+column__#1' => [
//					'@label' => 'Informations',
//					'@css_classes' => ['custom-container', 'container-flower', 'layout-grow'],
//					'+fieldset__#1' => [ 'birthday', 'city', 'tel'],
//				],
//				'+column__#2' => [
//					'@label' => 'Options',
//					'@css_classes' => ['custom-container', 'container-color', 'mb-3'],
//					'+fieldset__#1' => ['mode', 'interval'],
//				],
//
//			],
//			'+row__2' => [
//				'@label' => 'Telechargement',
//				'@css_classes' => ['custom-container', 'container-color2', 'mb-3'],
//				'+fieldset__#2' => ['file'],
//			]
//		];
//
//		// etendu // - lisible + organisé // free id
//		$aDescription3 = [
//
//			'1' => [
//
//				'properties' => [
//					'type' => 'row',
//					'css_classes' => 'custom-container container-flower layout-grow',
//					'rank' => 1
//				],
//
//				'content' => [
//
//					'11' => [
//
//						'properties' => [
//							'label' => 'Informations',
//							'type' => 'column',
//							'rank' => 1
//						],
//
//						'content' => [
//
//							'111' => [
//
//								'properties' => [
//									'type' => 'fieldset',
//									'rank' => 1
//								],
//
//								'content' => [ 'birthday', 'city', 'tel']
//							],
//						],
//
//					'22' => [
//
//						'properties' => [
//							'label' => 'Informations',
//							'type' => 'column',
//							'rank' => 1
//						],
//
//						'content' => [
//
//							'222' => [
//
//								'properties' => [
//									'type' => 'fieldset',
//									'rank' => 2
//								],
//
//							]
//
//						]
//					]
//
//				],
//
//			],
//			'2' => [
//
//				'properties' => [
//					'type' => 'row',
//					'css_classes' => 'custom-container container-color2 mb-3',
//					'rank' => 2
//				],
//
//				'content' => [
//
//					'properties' => [
//						'type' => 'column',
//						'rank' => 1
//					],
//
//					'111' => [
//
//				'css_classes' => 'custom-container container-color2 mb-3',
//				'fieldset__2' => ['file'],
//			]
//		];
//
//		return [$aDescription, $aDescription2];
//	}

}
.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Button
======

Class Button

----

.. include:: /manual/Component/Button/ButtonAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIButton**

:Syntax:
 
::

    {% UIButton Type {Parameters} %}

:Type:

+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| Neutral                         | Make a basis Button component for any purpose                                                                                    |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForPrimaryAction                | Make a Button component for a primary action, should be used to tell the user this is the main choice                            |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForSecondaryAction              | Make a Button component for a secondary action, should be used to tell the user this is an second hand choice                    |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForPositiveAction               | Make a Button component for a success action, should be used to tell the user he/she going to make a positive action/choice      |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForDestructiveAction            | Make a Button component for a destructive action, should be used to tell the user he/she going to make something that cannot be  |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| AlternativeNeutral              | Make a basis Button component for any purpose                                                                                    |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForAlternativePrimaryAction     | Make a Button component for an alternative primary action, should be used to avoid the user to consider this action as the first |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForAlternativeSecondaryAction   | Make a Button component for an alternative secondary action, should be used to avoid the user to focus on this                   |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForAlternativeValidationAction  | Make a Button component for a validation action, should be used to avoid the user to focus on this                               |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForAlternativeDestructiveAction | Make a Button component for a destructive action, should be used to avoid the user to focus on this                              |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| ForCancel                       | Make a Button component for a cancel, should be used only for UI navigation, not destructive action                              |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| IconAction                      | @param string $sIconClasses                                                                                                      |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| LinkNeutral                     | Make a link Button component to open an URL instead of triggering a form action                                                  |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| IconLink                        | @param string $sIconClasses                                                                                                      |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| DestructiveIconLink             | @param string $sIconClasses                                                                                                      |
+---------------------------------+----------------------------------------------------------------------------------------------------------------------------------+

:Button *Neutral* parameters:

+--------+--------+-----------+------+----------------------------+
| sLabel | string | mandatory |      |                            |
+--------+--------+-----------+------+----------------------------+
| sName  | string | optional  | NULL | See {@link Button::$sName} |
+--------+--------+-----------+------+----------------------------+
| sId    | string | optional  | NULL |                            |
+--------+--------+-----------+------+----------------------------+

:Button *ForPrimaryAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForSecondaryAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForPositiveAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForDestructiveAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *AlternativeNeutral* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForAlternativePrimaryAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForAlternativeSecondaryAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForAlternativeValidationAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForAlternativeDestructiveAction* parameters:

+-----------+--------+-----------+-------+---------------------+
| sLabel    | string | mandatory |       |                     |
+-----------+--------+-----------+-------+---------------------+
| sName     | string | optional  | NULL  | See Button::$sName  |
+-----------+--------+-----------+-------+---------------------+
| sValue    | string | optional  | NULL  | See Button::$sValue |
+-----------+--------+-----------+-------+---------------------+
| bIsSubmit | bool   | optional  | false | See Button::$sType  |
+-----------+--------+-----------+-------+---------------------+
| sId       | string | optional  | NULL  |                     |
+-----------+--------+-----------+-------+---------------------+

:Button *ForCancel* parameters:

+-----------+--------+----------+-------+---------------------+
| sLabel    | string | optional | NULL  |                     |
+-----------+--------+----------+-------+---------------------+
| sName     | string | optional | NULL  | See Button::$sName  |
+-----------+--------+----------+-------+---------------------+
| sValue    | string | optional | NULL  | See Button::$sValue |
+-----------+--------+----------+-------+---------------------+
| bIsSubmit | bool   | optional | false | See Button::$sType  |
+-----------+--------+----------+-------+---------------------+
| sId       | string | optional | NULL  |                     |
+-----------+--------+----------+-------+---------------------+

:Button *IconAction* parameters:

+--------------+--------+-----------+-------+--+
| sIconClasses | string | mandatory |       |  |
+--------------+--------+-----------+-------+--+
| sTooltipText | string | optional  | ''    |  |
+--------------+--------+-----------+-------+--+
| sName        | string | optional  | NULL  |  |
+--------------+--------+-----------+-------+--+
| sValue       | string | optional  | NULL  |  |
+--------------+--------+-----------+-------+--+
| bIsSubmit    | bool   | optional  | false |  |
+--------------+--------+-----------+-------+--+
| sId          | string | optional  | NULL  |  |
+--------------+--------+-----------+-------+--+

:Button *LinkNeutral* parameters:

+--------------+--------+-----------+------+--+
| sURL         | string | mandatory |      |  |
+--------------+--------+-----------+------+--+
| sLabel       | string | optional  | ''   |  |
+--------------+--------+-----------+------+--+
| sIconClasses | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+
| sTarget      | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+
| sId          | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+

:Button *IconLink* parameters:

+--------------+--------+-----------+------+--+
| sIconClasses | string | mandatory |      |  |
+--------------+--------+-----------+------+--+
| sTooltipText | string | mandatory |      |  |
+--------------+--------+-----------+------+--+
| sURL         | string | optional  | ''   |  |
+--------------+--------+-----------+------+--+
| sTarget      | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+
| sId          | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+

:Button *DestructiveIconLink* parameters:

+--------------+--------+-----------+------+--+
| sIconClasses | string | mandatory |      |  |
+--------------+--------+-----------+------+--+
| sTooltipText | string | mandatory |      |  |
+--------------+--------+-----------+------+--+
| sURL         | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+
| sName        | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+
| sTarget      | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+
| sId          | string | optional  | NULL |  |
+--------------+--------+-----------+------+--+

:Button common parameters:

+-------------------+--------+------------------------------------------------------------+
| ActionType        | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| AddCSSClass       | string | CSS class to add to the generated html block               |
+-------------------+--------+------------------------------------------------------------+
| AddCSSClasses     | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------+------------------------------------------------------------+
| AddCssFileRelPath | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| AddHtml           | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| AddJsFileRelPath  | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| CSSClasses        | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------+------------------------------------------------------------+
| Color             | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| DataAttributes    | array  | Array of data attributes in the format ['name' => 'value'] |
+-------------------+--------+------------------------------------------------------------+
| IconClass         | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| IsHidden          | bool   | Indicates if the block is hidden by default                |
+-------------------+--------+------------------------------------------------------------+
| JsCode            | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| Label             | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| OnClickJsCode     | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| Tooltip           | string |                                                            |
+-------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Button/ButtonFooter.rst

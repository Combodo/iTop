.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Button:

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

.. code-block:: twig

    {% UIButton Type {Parameters} %}

:Type:

+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`Neutral <ButtonNeutral>`                                                 | Make a basis Button component for any purpose                                                                                    |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForPrimaryAction <ButtonForPrimaryAction>`                               | Make a Button component for a primary action, should be used to tell the user this is the main choice                            |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForSecondaryAction <ButtonForSecondaryAction>`                           | Make a Button component for a secondary action, should be used to tell the user this is an second hand choice                    |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForPositiveAction <ButtonForPositiveAction>`                             | Make a Button component for a success action, should be used to tell the user he/she going to make a positive action/choice      |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForDestructiveAction <ButtonForDestructiveAction>`                       | Make a Button component for a destructive action, should be used to tell the user he/she going to make something that cannot be  |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`AlternativeNeutral <ButtonAlternativeNeutral>`                           | Make a basis Button component for any purpose                                                                                    |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForAlternativePrimaryAction <ButtonForAlternativePrimaryAction>`         | Make a Button component for an alternative primary action, should be used to avoid the user to consider this action as the first |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForAlternativeSecondaryAction <ButtonForAlternativeSecondaryAction>`     | Make a Button component for an alternative secondary action, should be used to avoid the user to focus on this                   |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForAlternativeValidationAction <ButtonForAlternativeValidationAction>`   | Make a Button component for a validation action, should be used to avoid the user to focus on this                               |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForAlternativeDestructiveAction <ButtonForAlternativeDestructiveAction>` | Make a Button component for a destructive action, should be used to avoid the user to focus on this                              |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`ForCancel <ButtonForCancel>`                                             | Make a Button component for a cancel, should be used only for UI navigation, not destructive action                              |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`IconAction <ButtonIconAction>`                                           | @param string $sIconClasses                                                                                                      |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`LinkNeutral <ButtonLinkNeutral>`                                         | Make a link Button component to open an URL instead of triggering a form action                                                  |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`IconLink <ButtonIconLink>`                                               | @param string $sIconClasses                                                                                                      |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+
| :ref:`DestructiveIconLink <ButtonDestructiveIconLink>`                         | @param string $sIconClasses                                                                                                      |
+--------------------------------------------------------------------------------+----------------------------------------------------------------------------------------------------------------------------------+

.. _ButtonNeutral:

Button Neutral
^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton Neutral {sLabel:'value', sName:'value', sId:'value'} %}

:parameters:

+--------+--------+-----------+------+----------------------------+
| sLabel | string | mandatory |      |                            |
+--------+--------+-----------+------+----------------------------+
| sName  | string | optional  | NULL | See {@link Button::$sName} |
+--------+--------+-----------+------+----------------------------+
| sId    | string | optional  | NULL |                            |
+--------+--------+-----------+------+----------------------------+

.. _ButtonForPrimaryAction:

Button ForPrimaryAction
^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForPrimaryAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForSecondaryAction:

Button ForSecondaryAction
^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForSecondaryAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForPositiveAction:

Button ForPositiveAction
^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForPositiveAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForDestructiveAction:

Button ForDestructiveAction
^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForDestructiveAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonAlternativeNeutral:

Button AlternativeNeutral
^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton AlternativeNeutral {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForAlternativePrimaryAction:

Button ForAlternativePrimaryAction
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForAlternativePrimaryAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForAlternativeSecondaryAction:

Button ForAlternativeSecondaryAction
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForAlternativeSecondaryAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForAlternativeValidationAction:

Button ForAlternativeValidationAction
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForAlternativeValidationAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForAlternativeDestructiveAction:

Button ForAlternativeDestructiveAction
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForAlternativeDestructiveAction {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonForCancel:

Button ForCancel
^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton ForCancel {sLabel:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonIconAction:

Button IconAction
^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton IconAction {sIconClasses:'value', sTooltipText:'value', sName:'value', sValue:'value', bIsSubmit:true, sId:'value'} %}

:parameters:

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

.. _ButtonLinkNeutral:

Button LinkNeutral
^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton LinkNeutral {sURL:'value', sLabel:'value', sIconClasses:'value', sTarget:'value', sId:'value'} %}

:parameters:

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

.. _ButtonIconLink:

Button IconLink
^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton IconLink {sIconClasses:'value', sTooltipText:'value', sURL:'value', sTarget:'value', sId:'value'} %}

:parameters:

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

.. _ButtonDestructiveIconLink:

Button DestructiveIconLink
^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIButton DestructiveIconLink {sIconClasses:'value', sTooltipText:'value', sURL:'value', sName:'value', sTarget:'value', sId:'value'} %}

:parameters:

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

Button common parameters
^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+--------+------------------------------------------------------------+
| ActionType        | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| AddCSSClass       | string | CSS class to add to the generated html block               |
+-------------------+--------+------------------------------------------------------------+
| AddCSSClasses     | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------+------------------------------------------------------------+
| AddCssFileRelPath | string | relative path of a CSS file to add                         |
+-------------------+--------+------------------------------------------------------------+
| AddHtml           | string |                                                            |
+-------------------+--------+------------------------------------------------------------+
| AddJsFileRelPath  | string | relative path of a JS file to add                          |
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

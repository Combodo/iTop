.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _ButtonGroup:

ButtonGroup
===========

Class ButtonGroup

----

.. include:: /manual/Component/ButtonGroup/ButtonGroupAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIButtonGroup**

:Syntax:
 
::

    {% UIButtonGroup Type {Parameters} %}

:Type:

+-----------------------------------------------------------------+--------------------------------------------------------------------------------------------------+
| :ref:`ButtonWithOptionsMenu <ButtonGroupButtonWithOptionsMenu>` | Make a button that has a primary action ($oButton) but also an options menu ($oMenu) on the side |
+-----------------------------------------------------------------+--------------------------------------------------------------------------------------------------+

.. _ButtonGroupButtonWithOptionsMenu:

ButtonGroup ButtonWithOptionsMenu
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIButtonGroup Type ButtonWithOptionsMenu {oButton:value, oMenu:value} %}

:parameters:

+---------+-------------+-----------+--+--+
| oButton | Button      | mandatory |  |  |
+---------+-------------+-----------+--+--+
| oMenu   | PopoverMenu | mandatory |  |  |
+---------+-------------+-----------+--+--+

ButtonGroup common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+----------+------------------------------------------------------------+
| AddButton         | Button   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddButtons        | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   | relative path of a CSS file to add                         |
+-------------------+----------+------------------------------------------------------------+
| AddExtraBlock     | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   | relative path of a JS file to add                          |
+-------------------+----------+------------------------------------------------------------+
| Buttons           | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| CSSClasses        | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| DataAttributes    | array    | Array of data attributes in the format ['name' => 'value'] |
+-------------------+----------+------------------------------------------------------------+
| IsHidden          | bool     | Indicates if the block is hidden by default                |
+-------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Component/ButtonGroup/ButtonGroupFooter.rst

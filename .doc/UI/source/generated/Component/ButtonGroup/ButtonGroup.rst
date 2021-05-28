.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

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

+-----------------------+--------------------------------------------------------------------------------------------------+
| ButtonWithOptionsMenu | Make a button that has a primary action ($oButton) but also an options menu ($oMenu) on the side |
+-----------------------+--------------------------------------------------------------------------------------------------+

:ButtonGroup *ButtonWithOptionsMenu* parameters:

+---------+-------------+-----------+--+--+
| oButton | Button      | mandatory |  |  |
+---------+-------------+-----------+--+--+
| oMenu   | PopoverMenu | mandatory |  |  |
+---------+-------------+-----------+--+--+

:ButtonGroup common parameters:

+-------------------+----------+------------------------------------------------------------+
| AddButton         | Button   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddButtons        | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddExtraBlock     | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   |                                                            |
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

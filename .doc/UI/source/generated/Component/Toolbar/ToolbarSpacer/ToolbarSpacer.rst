.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

ToolbarSpacer
=============

Class ButtonToolbarSpacer

----

.. include:: /manual/Component/Toolbar/ToolbarSpacer/ToolbarSpacerAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIToolbarSpacer**

:Syntax:
 
::

    {% UIToolbarSpacer Type {Parameters} %}

:Type:

+----------+-------------------------+
| Standard | @param string|null $sId |
+----------+-------------------------+

:ToolbarSpacer *Standard* parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

:ToolbarSpacer common parameters:

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
| DataAttributes    | array  | Array of data attributes in the format ['name' => 'value'] |
+-------------------+--------+------------------------------------------------------------+
| IsHidden          | bool   | Indicates if the block is hidden by default                |
+-------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Toolbar/ToolbarSpacer/ToolbarSpacerFooter.rst

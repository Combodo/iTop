.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _ToolbarSpacer:

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

.. code-block:: twig

    {% UIToolbarSpacer Type {Parameters} %}

:Type:

+-----------------------------------------+-------------------------+
| :ref:`Standard <ToolbarSpacerStandard>` | @param string|null $sId |
+-----------------------------------------+-------------------------+

.. _ToolbarSpacerStandard:

ToolbarSpacer Standard
^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIToolbarSpacer Standard {sId:'value'} %}

:parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

ToolbarSpacer common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
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
| DataAttributes    | array  | Array of data attributes in the format ['name' => 'value'] |
+-------------------+--------+------------------------------------------------------------+
| IsHidden          | bool   | Indicates if the block is hidden by default                |
+-------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Toolbar/ToolbarSpacer/ToolbarSpacerFooter.rst

.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _FieldBadge:

FieldBadge
==========

Class FieldBadge

----

.. include:: /manual/Component/FieldBadge/FieldBadgeAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIFieldBadge**

:Syntax:

.. code-block:: twig

    {% UIFieldBadge Type {Parameters} %}
        Content Goes Here
    {% EndUIFieldBadge %}

:Type:

+--------------------------------------+-----------------------+
| :ref:`ForField <FieldBadgeForField>` | @param string $sValue |
+--------------------------------------+-----------------------+

.. _FieldBadgeForField:

FieldBadge ForField
^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIFieldBadge ForField {sValue:'value', oStyle:value} %}
        Content Goes Here
    {% EndUIFieldBadge %}

:parameters:

+--------+----------+-----------+--+--+
| sValue | string   | mandatory |  |  |
+--------+----------+-----------+--+--+
| oStyle | ormStyle | mandatory |  |  |
+--------+----------+-----------+--+--+

FieldBadge common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   | relative path of a CSS file to add                         |
+-------------------+----------+------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   | relative path of a JS file to add                          |
+-------------------+----------+------------------------------------------------------------+
| AddSubBlock       | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| CSSClasses        | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| DataAttributes    | array    | Array of data attributes in the format ['name' => 'value'] |
+-------------------+----------+------------------------------------------------------------+
| DeferredBlocks    | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| IsHidden          | bool     | Indicates if the block is hidden by default                |
+-------------------+----------+------------------------------------------------------------+
| SubBlocks         | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Component/FieldBadge/FieldBadgeFooter.rst

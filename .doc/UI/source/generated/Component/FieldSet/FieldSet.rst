.. Copyright (C) 2010-2022 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _FieldSet:

FieldSet
========

Class FieldSet

----

.. include:: /manual/Component/FieldSet/FieldSetAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIFieldSet**

:Syntax:

.. code-block:: twig

    {% UIFieldSet Type {Parameters} %}
        Content Goes Here
    {% EndUIFieldSet %}

:Type:

+------------------------------------+------------------------+
| :ref:`Standard <FieldSetStandard>` | @param string $sLegend |
+------------------------------------+------------------------+

.. _FieldSetStandard:

FieldSet Standard
^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIFieldSet Standard {sLegend:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIFieldSet %}

:parameters:

+---------+--------+-----------+------+--+
| sLegend | string | mandatory |      |  |
+---------+--------+-----------+------+--+
| sId     | string | optional  | NULL |  |
+---------+--------+-----------+------+--+

FieldSet common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^

+-----------------------------+----------+------------------------------------------------------------+
| AddCSSClass                 | string   | CSS class to add to the generated html block               |
+-----------------------------+----------+------------------------------------------------------------+
| AddCSSClasses               | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath           | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddDeferredBlock            | iUIBlock |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddHtml                     | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath            | string   |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddMultipleCssFilesRelPaths | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddMultipleJsFilesRelPaths  | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| AddSubBlock                 | iUIBlock |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| CSSClasses                  | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+----------+------------------------------------------------------------+
| DataAttributes              | array    | Array of data attributes in the format ['name' => 'value'] |
+-----------------------------+----------+------------------------------------------------------------+
| DeferredBlocks              | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| HasForcedDiv                | bool     |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| IsHidden                    | bool     |                                                            |
+-----------------------------+----------+------------------------------------------------------------+
| SubBlocks                   | array    |                                                            |
+-----------------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Component/FieldSet/FieldSetFooter.rst

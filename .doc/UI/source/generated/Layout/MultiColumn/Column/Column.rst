.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Column:

Column
======

Class Column

----

.. include:: /manual/Layout/MultiColumn/Column/ColumnAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIColumn**

:Syntax:

.. code-block:: twig

    {% UIColumn Type {Parameters} %}
        Content Goes Here
    {% EndUIColumn %}

:Type:

+----------------------------------+------------+
| :ref:`Standard <ColumnStandard>` | No comment |
+----------------------------------+------------+
| :ref:`ForBlock <ColumnForBlock>` | No comment |
+----------------------------------+------------+

.. _ColumnStandard:

Column Standard
^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIColumn Standard {sId:'value'} %}
        Content Goes Here
    {% EndUIColumn %}

:parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

.. _ColumnForBlock:

Column ForBlock
^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIColumn ForBlock {oBlock:value, sId:'value'} %}
        Content Goes Here
    {% EndUIColumn %}

:parameters:

+--------+---------+-----------+------+--+
| oBlock | UIBlock | mandatory |      |  |
+--------+---------+-----------+------+--+
| sId    | string  | optional  | NULL |  |
+--------+---------+-----------+------+--+

Column common parameters
^^^^^^^^^^^^^^^^^^^^^^^^

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

.. include:: /manual/Layout/MultiColumn/Column/ColumnFooter.rst

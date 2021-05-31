.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Title:

Title
=====

Class Title

----

.. include:: /manual/Component/Title/TitleAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UITitle**

:Syntax:

.. code-block:: twig

    {% UITitle Type {Parameters} %}
        Content Goes Here
    {% EndUITitle %}

:Type:

+-----------------------------------------------+------------+
| :ref:`ForPage <TitleForPage>`                 | No comment |
+-----------------------------------------------+------------+
| :ref:`ForPageWithIcon <TitleForPageWithIcon>` | No comment |
+-----------------------------------------------+------------+
| :ref:`Neutral <TitleNeutral>`                 | No comment |
+-----------------------------------------------+------------+
| :ref:`Standard <TitleStandard>`               | No comment |
+-----------------------------------------------+------------+

.. _TitleForPage:

Title ForPage
^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UITitle ForPage {sTitle:'value', sId:'value'} %}
        Content Goes Here
    {% EndUITitle %}

:parameters:

+--------+--------+-----------+------+--+
| sTitle | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

.. _TitleForPageWithIcon:

Title ForPageWithIcon
^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UITitle ForPageWithIcon {sTitle:'value', sIconUrl:'value', sIconCoverMethod:'value', bIsMedallion:true, sId:'value'} %}
        Content Goes Here
    {% EndUITitle %}

:parameters:

+------------------+--------+-----------+-----------+--+
| sTitle           | string | mandatory |           |  |
+------------------+--------+-----------+-----------+--+
| sIconUrl         | string | mandatory |           |  |
+------------------+--------+-----------+-----------+--+
| sIconCoverMethod | string | optional  | 'contain' |  |
+------------------+--------+-----------+-----------+--+
| bIsMedallion     | bool   | optional  | true      |  |
+------------------+--------+-----------+-----------+--+
| sId              | string | optional  | NULL      |  |
+------------------+--------+-----------+-----------+--+

.. _TitleNeutral:

Title Neutral
^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UITitle Neutral {sTitle:'value', iLevel:value, sId:'value'} %}
        Content Goes Here
    {% EndUITitle %}

:parameters:

+--------+--------+-----------+------+--+
| sTitle | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| iLevel | int    | optional  | 1    |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

.. _TitleStandard:

Title Standard
^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UITitle Standard {oTitle:value, iLevel:value, sId:'value'} %}
        Content Goes Here
    {% EndUITitle %}

:parameters:

+--------+---------+-----------+------+--+
| oTitle | UIBlock | mandatory |      |  |
+--------+---------+-----------+------+--+
| iLevel | int     | optional  | 1    |  |
+--------+---------+-----------+------+--+
| sId    | string  | optional  | NULL |  |
+--------+---------+-----------+------+--+

Title common parameters
^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   |                                                            |
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

.. include:: /manual/Component/Title/TitleFooter.rst

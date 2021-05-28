.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

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
 
::

    {% UITitle Type {Parameters} %}
        Content Goes Here
    {% EndUITitle %}

:Type:

+-----------------+------------+
| ForPage         | No comment |
+-----------------+------------+
| ForPageWithIcon | No comment |
+-----------------+------------+
| Neutral         | No comment |
+-----------------+------------+
| Standard        | No comment |
+-----------------+------------+

:Title *ForPage* parameters:

+--------+--------+-----------+------+--+
| sTitle | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

:Title *ForPageWithIcon* parameters:

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

:Title *Neutral* parameters:

+--------+--------+-----------+------+--+
| sTitle | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| iLevel | int    | optional  | 1    |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

:Title *Standard* parameters:

+--------+---------+-----------+------+--+
| oTitle | UIBlock | mandatory |      |  |
+--------+---------+-----------+------+--+
| iLevel | int     | optional  | 1    |  |
+--------+---------+-----------+------+--+
| sId    | string  | optional  | NULL |  |
+--------+---------+-----------+------+--+

:Title common parameters:

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

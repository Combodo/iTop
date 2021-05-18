Title
=====

Display a title.

----

Output Result
-------------



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

+------------------------------+-----------------------------------------------------+
| *ForPage*                    | Create a *page title*                               |
+------------------------------+-----------------------------------------------------+
| *ForPageWithIcon*            | Create an *page title with icon*                    |
+------------------------------+-----------------------------------------------------+
| *Neutral*                    | Create an *generic title*                           |
+------------------------------+-----------------------------------------------------+

:Title common parameters:

+-------------------+--------+-----------+----------------------------------+
| *sTitle*          | string | mandatory | Title                            |
+-------------------+--------+-----------+----------------------------------+
| *sId*             | string | optional  | ID of the HTML block             |
+-------------------+--------+-----------+----------------------------------+

:ForPageWithIcon specific parameters:

+--------------------+--------+-----------+------------------------------------------------+
| *sIconUrl*         | string | mandatory | Icon URL                                       |
+--------------------+--------+-----------+------------------------------------------------+
| *sIconCoverMethod* | string | optional  | one of *contain* (default), *zoomout*, *cover* |
+--------------------+--------+-----------+------------------------------------------------+
| *bIsMedallion*     | bool   | optional  | displayed as medallion (default true)          |
+--------------------+--------+-----------+------------------------------------------------+

:sIconCoverMethod values:

    - *contain*: Icon should be contained (boxed) in the medallion, best for icons with transparent background and some margin around
    - *zoomout*: Icon should be a little zoomed out to cover almost all space, best for icons with transparent background and no margin around (eg. class icons)
    - *cover*: Icon should cover all the space, best for icons with filled background

:Neutral specific parameters:

+-------------------+---------+-----------+----------------------------------+
| *iLevel*          | integer | mandatory | Title level (1-5) 1 is biggest   |
+-------------------+---------+-----------+----------------------------------+


:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`

----

Examples
--------


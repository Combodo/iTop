## Subresource Integrity

If you are loading Highlight.js via CDN you may wish to use [Subresource Integrity](https://developer.mozilla.org/en-US/docs/Web/Security/Subresource_Integrity) to guarantee that you are using a legimitate build of the library.

To do this you simply need to add the `integrity` attribute for each JavaScript file you download via CDN. These digests are used by the browser to confirm the files downloaded have not been modified.

```html
<script
  src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"
  integrity="sha384-9mu2JKpUImscOMmwjm1y6MA2YsW3amSoFNYwKeUHxaXYKQ1naywWmamEGMdviEen"></script>
<!-- including any other grammars you might need to load -->
<script
  src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/go.min.js"
  integrity="sha384-WmGkHEmwSI19EhTfO1nrSk3RziUQKRWg3vO0Ur3VYZjWvJRdRnX4/scQg+S2w1fI"></script>
```

The full list of digests for every file can be found below.

### Digests

```
sha384-o2RvzuEpU78s+xoEWs/++kMfb71T2QoVpd5kcyephebSrCF7IBl82kpINu7hXFCB /es/languages/apache.js
sha384-T5Wi3CdvOgNTDa/TQF3guYZxiCaiLFKzgYxX57ZCt1rVax+G6GP9EOlNdg8spAUs /es/languages/apache.min.js
sha384-uBlc/xEFeDxZmBU7K/YWwi3ryXQrLQCAY2K1Dl3OD2DaAQBZZTt6Ew3aeDP20ix0 /es/languages/bash.js
sha384-4qer4rJCVxZjkwD4YaJfOnT2NOOt0qdjKYJM2076C+djiJ4lgrP1LVsB/MCpJSET /es/languages/bash.min.js
sha384-69o7b7hP4COEFD4DUeWD/evW62WGzW4jk3uIuQ5l8Ogi2TM2ICVVaUYO8DTmIktm /es/languages/coffeescript.js
sha384-hs3Egk+9CzC2kK7ZZLBVUrgiyFcI/OzhECFhJzoD2Qm2ipxDbrwCTQAh/RTkuo6c /es/languages/coffeescript.min.js
sha384-Wjwd1YEG/PYlkLHTWIx+RlPK6XboMN3bEpveERJ8D8Z4RaNE02Ho19ZFrSRPGi0j /es/languages/cpp.js
sha384-Q4zTNH8WsDVdSZbiZtzWS1HmAUcvMSdTmth9Uqgfjmx7Qzw6B8E3lC9ieUbE/9u4 /es/languages/cpp.min.js
sha384-Sp8E1Lb9fNhbvqBiogM60TCgpAIwYBi8WbHhIHcXO0bR5Z+9LeYwpDa1gkjzU99W /es/languages/csharp.js
sha384-huUb4Ol37G1WrtGV7bn1UArXcJSjD4tQswMGzgpNZYAPxR74MHTqW79z1dXWMvhk /es/languages/csharp.min.js
sha384-DA5ii4oN8R2fsamNkHOanSjuN4v7j5RIuheQqnxMQ4cFnfekeuhwu4IdNXiCf+UU /es/languages/css.js
sha384-OBugjfIr093hFCxTRdVfKH8Oe3yiBrS58bhyYYTUQJVobk6SUEjD7pnV8BPwsr8a /es/languages/css.min.js
sha384-nJZn4t0S58K5MJ/2BOmgkTC8TtBWB76ykEKIIzb8sUxT3h7ouZH5Ucqnr9MJ6Bry /es/languages/diff.js
sha384-OIJrZgyhN1E6e29B9M3gFPtkR5JgXpls8h5D2bNzBxQ8Ncwxzfjedu3/0jZEe7S9 /es/languages/diff.min.js
sha384-MIy8GI9hoOfmj9i8J9ET6pQfhZRSy2avHKawR4XrLC/TA0KTWF2UvSCfRGxtC9uk /es/languages/dns.js
sha384-BZdQkDZ1ac9zSg4XmtL2hLKld98ttTuqoM6CWOjKIrT16NkmNSlAY/1rK48DQjqy /es/languages/dns.min.js
sha384-7m427rj9OYl8KKObOk+aD0QhXmqXy6hU/sHGS6OUwW9hRiiHvr+tLTbLiSJYbIyD /es/languages/http.js
sha384-GdKwMgTrO0R3SPXhm+DuaN3Qr7WkqYmmTClINAGPuV/BUPE9WUI/WnTEntp522Sl /es/languages/http.min.js
sha384-7hMYwsnoFLW0Wnjv4vRnZxuedW1tCz7/pydl1b9w3fg7B+rldToCjqzXwCRHUE7C /es/languages/ini.js
sha384-LDu6uT3diI3jBUJtdoGFa787cYlVrR+aqFfmW+kW+TImOkpVe+P5LBdDzydIHo9Z /es/languages/ini.min.js
sha384-WFJdA9Hz+G9NQx5vPba/tcGyIibm57UkKVY32wNB/94iT2FmPma5W7gY8p2l6qps /es/languages/java.js
sha384-coaxfgI2lKuDqSxfMlfyPq5WM0THaLGyATZHzaFMrWdIbPcLdduuItTe6AmT/m33 /es/languages/java.min.js
sha384-WCznKe2n87QvV/L1MlXN+S8R6NPUQGU34+AqogMuWGZJswSD6rt3Mgih+xuKlDgm /es/languages/javascript.js
sha384-eGsBtetyKPDKaLiTnxTzhSzTFM6A/yjHBQIj4rAMVaLPKW5tJb8U6XLr/AikCPd+ /es/languages/javascript.min.js
sha384-12GbYFzdyZCSmfBTmO2CR/qE89K5uE1PEuJ3QUwXH0Q9u+uoLNigjH9dG7LAxxiI /es/languages/json.js
sha384-+DT7AtubDhVDciRc6CgjJJRsCt0L8NC3Dh8n9Tj2tZWU8rWxDIj1ViubmUDn8OCY /es/languages/json.min.js
sha384-UgQTewauLJ4EgpADCJ99JfEtiPvw+fyaSrY1gtCVBviDNG7yCH5U7qutYptSfYk+ /es/languages/makefile.js
sha384-aUXBqKsjOzPD/W+hccF21KKWmWts/CrY/lWGJU+dAcsKtuh1/XtyDnzfLmqy/fV1 /es/languages/makefile.min.js
sha384-bWwkdmOCj83zZ8/m+oPD9goRMhrPCb25ZA6aTyg7vcsq9IpuyED38kQSw1Na4UTZ /es/languages/markdown.js
sha384-SqGSUq0DMQ0OUQnQnTuVDCJyhANd/MFNj+0PF67S+VXgHpR8A4tPsf/3GoSFRmrx /es/languages/markdown.min.js
sha384-ohJ9Jj8Mwyy7EntP4tGMJEtH90WJNKu2C4l37N1kqyTHgbRJyGYUgFe9z3qy3/C8 /es/languages/nginx.js
sha384-tpFPEHvbpL3dYF4uFiVNuCUF62TgMzuW65u5mvxnaJYun1sZwxBsKv+EvVgv3yQK /es/languages/nginx.min.js
sha384-3ETIPQZ3mDkQp0yO3vWqaxcOnISPBk4oE4DrkgRfbMW6rhFcvu7N/P8tucGbapAC /es/languages/objectivec.js
sha384-eSkOKizj8aZA0WJaMmTJA3uqUtTkbZ7PXAADzDquzxCLyb1BMNgF9cn7/FIu0wFR /es/languages/objectivec.min.js
sha384-B+EjdRTJfuRQL8OfQPSh3FpJXyftaETF0bCfUNAm34365HyQ0yM6cjh+8qYsSEE2 /es/languages/perl.js
sha384-kW4vu/krgDKKOlEd71XhqvOJNxZuQ9t5Rwutcxm3/zfujAXe08IcLMX5Al0NGE72 /es/languages/perl.min.js
sha384-JBkI+6623OoC1qCgG+MY/Ta0qRYSzTDH4NGMA+7U8RNOjkh7geFvYpRidvdHs3zT /es/languages/php.js
sha384-6Znh5S5q9F0hGNMtdaihFL3RJeMqL1a/UecjgzaBrhoxe4sjd5aiEOgokt7blhSW /es/languages/php.min.js
sha384-TTDGPCrk8Dg2oW6NghGM5WJQPbi34BdYJj6yfsDiGXlM5os/SebXT6KzATp19rzo /es/languages/plaintext.js
sha384-XXx7wj9KPm08AyGoGzzFKZP2S7S+S5MbKMPnQcWUyhJ3EjHvLuctK/O1ioJnG2ef /es/languages/plaintext.min.js
sha384-+5oyk7Ed3OlvEWGj8xracq/6e52BScKUN4kxcreNwB7kfRTVsAMs/aAJM58dzIFN /es/languages/python.js
sha384-ND/UH2UkaeWiej5v/oJspfKDz9BGUaVpoDcz4cof0jaiv/mCigjvy7RQ7e+3S6bg /es/languages/python.min.js
sha384-vs4jXytP3N3d5zRX15Fqc4u/kDI5jDr3PNYIvQ0mmmoU+o/yxJfu/+VYme6qIOa2 /es/languages/ruby.js
sha384-O4a+vELXT191NhKLE3TR5WQwDmQZ2izAhb2zETRxcPSXr6CJruqJ4a+GJpDlaqCF /es/languages/ruby.min.js
sha384-I4aH0szMeaCbcs8R7dhxA3p7ZBL/HFxnD5Gbz6l52kIrd/igSSFi/9sJCykNuL52 /es/languages/rust.js
sha384-1vvSh2x0WCtPLbkTMqNuf8JSZw8N5bSo9oONZ9vqU8NOBHPIuKt+kFdC8G5nA+P1 /es/languages/rust.min.js
sha384-lRhSX2XDrY27NzrAS1t4YaeRtwjsY41kFBbIEYltkmnsfSE7lbBJMQVds2u/MqTT /es/languages/scss.js
sha384-RDUehV4j9Do6iGkYq9Gjn3aUxh6x+NFER1sHpLUXsNoCFjah8Ysrlad8ukLbIr4J /es/languages/scss.min.js
sha384-dzLjhd9nNJH62idgKI1vZEKHRBtZXSgwWQdPR+emG7tfAN4BW2g+A5Xs2315Uxii /es/languages/shell.js
sha384-RKUoelG22/D7BV/bNpoGLNzdTgWRf/ACQX7y4BGyIzK6E+xUoXtm68WNQW2tSW8X /es/languages/shell.min.js
sha384-rBAFhyrcRcMNbVJ9g4k5y3eQDkjGdgoOb3oTWTbHgwyUgUNv3CK9wLsGy/d+52oa /es/languages/sql.js
sha384-8G3qMPeOeXVKZ0wGzMQHgMVQWixLw3EXFAcU+IFNLRe0WoZB5St3L3ZLTK62Nzns /es/languages/sql.min.js
sha384-GeSC35L2abDL10oBSI0dwmV6CU8FxO8EJn5fTlIaHC8o57sZ28pgPCzDRFfjo410 /es/languages/twig.js
sha384-Bll0N6Bv3PBNceR+4FI/xj/vKUrRYkGyy0oHArQg7yjocZ4goBU/MlbynwkpxynM /es/languages/twig.min.js
sha384-CS3qiWid6Sod3yAiQwgPzy2ZerR00u/cwhnMxQrETuI74o006r1p5qj1U9Gdo4uD /es/languages/typescript.js
sha384-HHgazax8ozQ9RDWlJQyaFzaTk/OgTFRVHH+lcaYInkE8wVu5fnpkqkB3KUdzKcvE /es/languages/typescript.min.js
sha384-OE0Tch4fj9YpsZ5GehsIC+qt2LYAyGlva8PjzC97pmX6PUCR90+b9ZMmoKSQLSI5 /es/languages/vbscript.js
sha384-+GEu9SSqFFm1Oorzq3xLm59JzaVcoELsmjRU9e2/5+qKtgBvEqt98SfCW5RQ2vQ7 /es/languages/vbscript.min.js
sha384-OFoR8IZ+CFwcY8plx8HSDZNoCwLxc701CwdNGfoIEhSgwAbwhvInaxnEi3HYTt2Q /es/languages/xml.js
sha384-yFd3InBtG6WtAVgIl6iIdFKis8HmMC7GbbronB4lHJq3OLef3U8K9puak6MuVZqx /es/languages/xml.min.js
sha384-MX3xn8TktkjONV3aWF6Qn6WZyq2Lh/98p0v7D0qEoJ4WLdYjoAyXF/L/80q3qaEc /es/languages/yaml.js
sha384-4IiaMbQ0LBiRJYBGoAXsN+dV9qu/cGLES6IuVowdeCu/FAMY5/MQfD/bHXoL2YBb /es/languages/yaml.min.js
sha384-hK+QzfKnkrwE7Fgc3q6lpKfnWWKMecpkQObMVOPiwopZiJni16bBGVm37SMGcz6G /languages/apache.js
sha384-bvrESIqIr21pkx86VgiN+Vfq8sRk/6xTxMfbo0PDENLfqYr123El7PUCzqJmir4P /languages/apache.min.js
sha384-qbbaBGYYg7PdopdWOGj8KdkBosUDY5PAe3aTMJKTqWcriPBJJzCVu5BlwNEwqr9U /languages/bash.js
sha384-ByZsYVIHcE8sB12cYY+NUpM80NAWHoBs5SL8VVocIvqVLdXf1hmXNSBn/H9leT4c /languages/bash.min.js
sha384-WUNn1g8nanfA0JIzB9I7thYK5lEx4KCzTXft6eLUiSXrFT8gHOk4MU9CHr/J4EB4 /languages/coffeescript.js
sha384-LAzSkWYZ3FWRTDwn+LHzyO1jGUFT5FYSAABzOTE4lsm429sEXm0+U7A5i73hhMSz /languages/coffeescript.min.js
sha384-J4Ge+xXjXgzbK2FP+OyzIGHLfKU/RR0+cH4JJCaczeLETtVIvJdvqfikWlDuQ66e /languages/cpp.js
sha384-LMyrRAiUz6we2SGvYrwDd4TJoJZ+m/5c+4n4E64KVkfWFcZdlrs4Wabr0crMesyy /languages/cpp.min.js
sha384-8sbRwiU8Ar2M7+w//1u7YiI1e7KsmB4k3QbW/m1IW5FVH51HiOpR7g5QGE3RqTNi /languages/csharp.js
sha384-wWP4JQEhRVshehTP7lUMDn3yhDI2+398vN2QW5LBt1xIpK0Gfu4dPviO8tP9XRo5 /languages/csharp.min.js
sha384-r9czyL17/ovexTOK33dRiTbHrtaMDzpUXW4iRpetdu1OhhckHXiFzpgZyni2t1PM /languages/css.js
sha384-HpHXnyEqHVbcY+nua3h7/ajfIrakWJxA3fmIZ9X9kbY45N6V+DPfMtfnLBeYEdCx /languages/css.min.js
sha384-d6WNn2kPYgdezhgRmGehSkGVKwbg+ImJrGTA43yYg8VsBqYW5TakSDi/jbVjzqfD /languages/diff.js
sha384-yE0kUv4eZRBzwnxh0XmeqImGvOLywSpOZq2m0IH40+vBtkjS716NYEZyoacZVEra /languages/diff.min.js
sha384-fqA1g+qpOamYxlJhxcdB70bFCuVXyCGBytozVm1cBnm4XM+eGfkKcY1eZST3noz4 /languages/dns.js
sha384-ThDmUDUbkLHd3YjqnAKK6DGmCqm/zinzT9ezHFHk+GPnKaM0dvEM3KfFeoQUt8KH /languages/dns.min.js
sha384-g/lhU6FXH73RQL4eFwkPk4CuudGpbHg6cyVZCRpXft3EKCrcQTToBVEDRStjYWQb /languages/http.js
sha384-wt2eEhJoUmjz8wmTq0k9WvI19Pumi9/h7B87i0wc7QMYwnCJ0dcuyfcYo/ui1M6t /languages/http.min.js
sha384-JSUMPR+WT0h/7NlqXi1Al9bVlNT31AeZNpAHttuzu+r02AmxePeqvsZkKqYZf06n /languages/ini.js
sha384-QrHbXsWtJOiJjnLPKgutUfoIrj34yz0+JKPw4CFIDImvaTDQ/wxYyEz/zB3639vM /languages/ini.min.js
sha384-pYIeBYeCE96U9EkPcT4uJjNWyrB1BKB41JIadYJbvmGa5KacaoXtSQOUpBfeyWQX /languages/java.js
sha384-uUg+ux8epe42611RSvEkMX2gvEkMdw+l6xG5Z/aQriABp38RLyF9MjDZtlTlMuQY /languages/java.min.js
sha384-vJxw3XlwaqOQr8IlRPVIBO6DMML5W978fR21/GRI5PAF7yYi2WstLYNG1lXk6j9u /languages/javascript.js
sha384-44q2s9jxk8W5N9gAB0yn7UYLi9E2oVw8eHyaTZLkDS3WuZM/AttkAiVj6JoZuGS4 /languages/javascript.min.js
sha384-dq9sY7BcOdU/6YaN+YmFuWFG8MY2WYJG2w3RlDRfaVvjdHchE07Ss7ILfcZ56nUM /languages/json.js
sha384-RbRhXcXx5VHUdUaC5R0oV+XBXA5GhkaVCUzK8xN19K3FmtWSHyGVgulK92XnhBsI /languages/json.min.js
sha384-LmfE+sO0d5qZL2Ka0DIrgJ/5U1plo4uFFAmgjcMxrQO+RkeWVYWuaphHAdrY9g7V /languages/makefile.js
sha384-NIrob3StFQyD/nlOsXVCeRsJ0N2SvFEDjFtYS393wbD3CY1eT+2kwT4RL7tpMMhs /languages/makefile.min.js
sha384-U+zIQPoVdPCO0o4poik2hYNbHtNm+L5OojDTulgIeEZTNz+LooLAm72d66mNjwKD /languages/markdown.js
sha384-mCUujHHbWJEjcupTTfWOk9YR3YCYNHaA578+TTXUd4LPi7fGNuMQbysbl1pmcIGd /languages/markdown.min.js
sha384-OtoDZeQykEsQoFzaA50vgmgr+D3F8WIaXZ0CZfYFivHEpkwCpRKT5Ptmuhjr5bfJ /languages/nginx.js
sha384-p6UV3HZc7zs9wUF6j4Knk3bpx1/JnhlwDoFnKJAOogB1cUi32u31NlnZ2s2dW8IR /languages/nginx.min.js
sha384-Cc1k3KuhHQqdKFz/qLsQbf9E1rahGF9daZ9proh9kVL6eww2g3xEHhMLe7YQka0z /languages/objectivec.js
sha384-laCnSBtpGFl9ww6pMsbiDiJocbu+HlV9qgfWjOoArDqQrnlxH3Qm9kia5N2cicKM /languages/objectivec.min.js
sha384-YKOtRjfzHo4SHG+iI5acI0B8GrsAlqbQpZzAKKR/oABDfo0Qw6fdjwWtHY1He+ih /languages/perl.js
sha384-J8kCjl82Po/VNpumPop593F3EckTvJlnUS2ugGW46Zjts8BPGl6iNkXjcenKcwCx /languages/perl.min.js
sha384-S1JDGPScVg8ikNKLZc4CSP0ZxLiJ7bOJMzTLfOzQiCxR6wPqAa+YtauHJXQpc2GV /languages/php.js
sha384-c1RNlWYUPEU/QhgCUumvQSdSFaq+yFhv3VfGTG/OTh8oirAi/Jnp6XbnqOLePgjg /languages/php.min.js
sha384-IHapUcPkNR+7JNsR+qYSVYGCE3Dpzo2//VYWtmGYrw3eQG1RItQ7HYq6aK1Jo/6L /languages/plaintext.js
sha384-ofjxHpechXkaeQipogSyH62tQNaqAwQiuUHOVi4BGFsX5/KectIoxz16f8J/P5U0 /languages/plaintext.min.js
sha384-zdZio5RcGiKQJCpe/1IXujPle3bIY8sbmvCabSU5G5GzWAzZtoRZfg9QAQXCL08q /languages/python.js
sha384-IP4vv4Aoh9Lyg8QyzVkAmn2JGoDCpgVHzVSrD3Z+rVyn7+s4wx4pRjv+go3TEwfj /languages/python.min.js
sha384-AE7f30oAuQtqmFee9hPd2uoo44ZkyUY2wQL7DjjENhh2wrvS6q4mpxGtAxeCxiRQ /languages/ruby.js
sha384-pFZpTUpdH4YEXSenc9hfKZ4uCv2IQoJQCIlIHpA0fM2cvTVH8LuzQMNcGSRGeJG0 /languages/ruby.min.js
sha384-CA6FQ5i08WYjgGIhQBrXKmcJg42apGjTP9b5WqttVw3cYEtXwHHGo+XJLYS7u7F2 /languages/rust.js
sha384-ZQJ5PCEftpFqCZkLDs96CSDGddxBultwqTdlxjnJ5h2doMAQv0n1x66w7T/JQEyy /languages/rust.min.js
sha384-fwYddFsITuK2bPhi9RuIzwi4PTULEXgtEJsQzTdx97vOS/GHfrk+aNSLxEHgzQa5 /languages/scss.js
sha384-6u+QpCDqQidb5pcO+yBqy0xLJ30x30VlrFvXm8J84LMwGIw9q3U4u+Z9vFXlhB5x /languages/scss.min.js
sha384-KW3ZDReTAemYUfVHvH1MNQ/v6agCYYdMGdMteP/yVV+NetIJeDMx0ruUMTbr/SD3 /languages/shell.js
sha384-PDEMny3uYpCrSKwqVGLAwqCsc9u+9hYXauxVPqO6RXmlxSNKLoRByEBigU5lahmK /languages/shell.min.js
sha384-Dy7I/j0yJlyliWiNrkNqXfxDrbN65q40s3JColgTYZQ7QJa7lcmK0WUL3i00/T51 /languages/sql.js
sha384-8q00eP+tyV9451aJYD5ML3ftuHKsGnDcezp7EXMEclDg1fZVSoj8O+3VyJTkXmWp /languages/sql.min.js
sha384-M6Xb+A01UtgwR4nr8CBs6F51q0xe6hLSjT0AErnSSBeYKnEYWLmsaCn2H+WF/96/ /languages/twig.js
sha384-+o+wt9LKrJr093fuXe59myi53sotEZ0z9+zFloHncK3RudScyrwBfGFj8oW5urR+ /languages/twig.min.js
sha384-yZXtQC/OmWoPykosK7vE1nCvV4E/six6+apjNau4JwBkejkea5nP7VBEJJkGnvoF /languages/typescript.js
sha384-ORwtVEfrCZ0gzGacgmfv1wOtxcPIaVfHKwq8dKQjObRwx3qpKjsSg1ldTu1PEgXd /languages/typescript.min.js
sha384-ExivfVSytwDgcpGykhEMnzW9GjtspAVnDPc+GKjsYnh2tczWA9FLwz8dOFuA70WS /languages/vbscript.js
sha384-51jdo37NeOkYYUHq3Pv0sCaoGJOAFt9I2CIGGoQKDgY7YnquSMoA6f7FjfkxfZbb /languages/vbscript.min.js
sha384-+PuZYFfVX2UQZU2yKt/FsJUZNUPzZWxW7auXltsaecr1xLvzBYF3c5gYoyOs1++x /languages/xml.js
sha384-jgkY4GMNWfQcLLIoP1vg3FWXflDrRhcSXGBW6ONIWC2SOIv5H1Pa57sXs+aomCuZ /languages/xml.min.js
sha384-tB5cwwsX4Ddp7P4d+ZInDb3nt4ihEEglHXoQ18eVLlT7soEn7bfGfABWKIn1l+H2 /languages/yaml.js
sha384-WC56y8OaFPt5Kj2HX6JAumxUYEjQmBDcSTJy2pn/N8g7dg1hKjeNVrJYoxlpeVmz /languages/yaml.min.js
sha384-yhz9LuzFKvoUcS2VeIYPGpedhmMuppdN0HDbjFIdjOaA+QK6X6+Ws0RWYnZRKEhL /highlight.js
sha384-ZZtNmksr4NVCYavJmCVoOYaFY1m6gCWHihHYtdgHC179I7lLqg6u1zRfsEq21H3j /highlight.min.js
```


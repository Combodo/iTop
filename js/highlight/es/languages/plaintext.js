/*! `plaintext` grammar compiled for Highlight.js 11.9.0 */
var hljsGrammar = (function () {
  'use strict';

  /*
  Language: Plain text
  Author: Egor Rogov (e.rogov@postgrespro.ru)
  Description: Plain text without any highlighting.
  Category: common
  */

  function plaintext(hljs) {
    return {
      name: 'Plain text',
      aliases: [
        'text',
        'txt'
      ],
      disableAutodetect: true
    };
  }

  return plaintext;

})();
;
export default hljsGrammar;
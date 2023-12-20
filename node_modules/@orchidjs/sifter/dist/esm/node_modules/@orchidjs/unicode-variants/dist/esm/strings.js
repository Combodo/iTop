/*! sifter.js | https://github.com/orchidjs/sifter.js | Apache License (v2) */
/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */

/**
 * Get all possible combinations of substrings that add up to the given string
 * https://stackoverflow.com/questions/30169587/find-all-the-combination-of-substrings-that-add-up-to-the-given-string
 * @param {string} input
 * @return {string[][]}
 */
const allSubstrings = input => {
  if (input.length === 1) return [[input]];
  /** @type {string[][]} */

  let result = [];
  const start = input.substring(1);
  const suba = allSubstrings(start);
  suba.forEach(function (subresult) {
    let tmp = subresult.slice(0);
    tmp[0] = input.charAt(0) + tmp[0];
    result.push(tmp);
    tmp = subresult.slice(0);
    tmp.unshift(input.charAt(0));
    result.push(tmp);
  });
  return result;
};

export { allSubstrings };
//# sourceMappingURL=strings.js.map

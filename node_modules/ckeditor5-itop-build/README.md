# CKEditor 5 editor customized for iTop

## Installation

In order to build the editor you need to install all dependencies first. To do it, open the terminal in the project directory and type:

```
npm install
```

Make sure that you have the `node` and `npm` installed first. If not, then follow the instructions on the [Node.js documentation page](https://nodejs.org/en/).

## Adding or removing plugins

If you need to add additional CKEditor plugins, you can install them in the build. Just follow the [Adding a plugin to an editor tutorial](https://ckeditor.com/docs/ckeditor5/latest/installation/plugins/installing-plugins.html#adding-a-plugin-to-an-editor)

If you need to build your own plugins check CKEditor's documentation or `src/plugins/` for examples.

## Rebuilding editor

If you have already done the [Installation](#installation) and [Adding or removing plugins](#adding-or-removing-plugins) steps, you're ready to rebuild the editor by running the following command:

```
npm run build
```

This will build the CKEditor 5 to the `build` directory.

## Import your changes to iTop

In order to update iTop CKEditor's build you need to push your changes to github.
Then run the following command in iTop's root directory:

```
 npm install https://github.com/Combodo/ckeditor5-itop-build.git --omit=dev
```
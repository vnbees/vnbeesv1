const { merge } = require("webpack-merge");
const singleSpaDefaults = require("webpack-config-single-spa-react-ts");

module.exports = (webpackConfigEnv, argv) => {
  const defaultConfig = singleSpaDefaults({
    orgName: "app",
    projectName: "workflow-management",
    webpackConfigEnv,
    argv,
  });

  return merge(defaultConfig, {
    externals:['@app/utility']
    // modify the webpack config however you'd like to by adding to this object
  });
};

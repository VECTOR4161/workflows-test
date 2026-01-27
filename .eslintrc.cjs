module.exports = {
    root: true,
    env: {
        node: true,
        browser: true,
        es2021: true,
    },
    extends: ["eslint:recommended", "plugin:vue/vue3-recommended"],
    parserOptions: {
        ecmaVersion: "latest",
        sourceType: "module",
    },
    rules: {
        "vue/multi-word-component-names": "off",
        "vue/no-v-html": "warn",
        "no-console": process.env.NODE_ENV === "production" ? "warn" : "off",
        "no-debugger": process.env.NODE_ENV === "production" ? "warn" : "off",
        "vue/max-attributes-per-line": [
            "error",
            {
                singleline: 3,
                multiline: 1,
            },
        ],
        "vue/html-indent": ["error", 4],
        "vue/singleline-html-element-content-newline": "off",
        "vue/html-self-closing": [
            "error",
            {
                html: {
                    void: "always",
                    normal: "never",
                    component: "always",
                },
            },
        ],
    },
};

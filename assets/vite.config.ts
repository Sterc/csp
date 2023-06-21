import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import ai from 'unplugin-auto-import/vite'
import eslint from 'vite-plugin-eslint'
import components from 'unplugin-vue-components/vite'
import pages from 'vite-plugin-pages'
import {BootstrapVueNextResolver} from 'unplugin-vue-components/resolvers'

export default defineConfig({
  plugins: [
    vue(),
    eslint(),
    ai({
      dts: true,
      include: [/\.[tj]sx?$/, /\.vue$/, /\.vue\?vue/],
      imports: ['vue', 'vue-router'],
      dirs: ['src/composables'],
    }),
    components({
      directoryAsNamespace: true,
      resolvers: [BootstrapVueNextResolver()],
    }),
    pages(),
  ],
  server: {
    host: '0.0.0.0',
    port: 9090,
  },
  base: '/assets/components/sterc-csp/',
  build: {
    manifest: true,
    emptyOutDir: true,
    outDir: './dist',
    rollupOptions: {
      treeshake: 'smallest',
      input: './src/main.ts',
      output: {
        chunkFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith('.vue_vue_type_script_setup_true_lang')) {
            return `assets/${assetInfo.name.slice(0, -36)}-[hash:8].js`
          }
          return 'assets/[name]-[hash:8].js'
        },
      },
    },
  },
})

/**
 * Start a live reload server for development
 * Anytime files are changed, the browser will be reloaded
 * You will need to install the chrome extension:
 * https://chromewebstore.google.com/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei?hl=en
 *
 * Not perfect, just causes the browser to refresh the page.
 * Does not do partial or diff loads, nor will it save the state
 */
import livereload from 'livereload';

const liveReloadServer = livereload.createServer();
liveReloadServer.watch(['app', 'resources/views']);

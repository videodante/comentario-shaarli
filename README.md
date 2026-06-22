# comentario-shaarli
A plugin to integrate a comentario server's comments system into shaarli.

Based heavily on the official Shaarli (https://github.com/shaarli/Shaarli) isso plugin, I really just modified a few lines to make it work with comentario instead. 

I am hardly an experienced coder, but this should work fine.

To use, edit `comentario.html` to your server's comentario script, then drop the entire `comentario` folder into the `plugins` folder within your shaarli installation, then configure the COMENTARIO_SERVER_URL in Shaarli's admin configuration.

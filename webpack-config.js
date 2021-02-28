const path = require( 'path' );
// const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

const config = {

	// mode: 'development',
	watch: true,

	watchOptions: {
		poll: true,
		ignored: /node_modules/
	},

	// plugins: [
	// 	new BrowserSyncPlugin({
	// 		// browse to http://localhost:3000/ during development,
	// 		// ./public directory is being served
	// 		host: 'localhost',
	// 		port: 3000,
	// 		server: {baseDir: ['public']}
	// 	  })],
	
	entry: {
		frontend: './src/frontend/app.js',
        backend: './src/backend/app.js'
	},

	output: {
		// [name] allows for the entry object keys to be used as file names.
		filename: 'js/[name].js',
		// Specify the path to the JS files.
		path: path.resolve( __dirname, 'assets' )
	},

	module: {
		rules: [
			{
				// Look for any .js files.
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'babel-loader'
			},
            {
				test: /\.s[ac]ss$/i,
				use: [
					'style-loader',
					'css-loader',
					{
						loader: 'sass-loader',
						options: {
							implementation: require('sass'),
						},
					},
				],
			},
			{
				test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
				use: [
						{
						loader: 'file-loader',
						options: {
						name: '[name].[ext]',
						outputPath: 'fonts/'
						}
					}
				]
			}
		]
	}
}

// Export the config object.
module.exports = config;
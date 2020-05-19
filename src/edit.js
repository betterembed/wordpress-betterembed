import { Component } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';

import EmbedControls from './embed-controls';
import EmbedPlaceholder from './embed-placeholder';
import icon from './icon';
import { fallback } from './util';

export default class BetterEmbed extends Component {

	constructor() {
		super(...arguments);
		this.switchBackToURLInput = this.switchBackToURLInput.bind( this );
		this.setUrl = this.setUrl.bind( this );
		this.fallback = this.fallback.bind( this );
		this.state = {
			editingURL: false,
			url: this.props.attributes.url,
		};
	}

	switchBackToURLInput() {
		this.setState( { editingURL: true } );
	}

	setUrl( event ) {
		if ( event ) {
			event.preventDefault();
		}
		const { url } = this.state;
		const { setAttributes } = this.props;
		this.setState( { editingURL: false } );
		setAttributes( { url } );
	}

	fallback( url, onReplace ) {
		const link = <a href={ url }>{ url }</a>;
		onReplace(
			createBlock( 'core/paragraph', { content: renderToString( link ) } )
		);
	}

	render() {
		const { attributes } = this.props;
		const { url, editingURL } = this.state;

		if ( ! attributes.url || editingURL ) {

			return (
				<EmbedPlaceholder
					icon={ icon }
					label={ 'Better Embed' }
					onSubmit={ this.setUrl }
					value={ url }
					onChange={ ( event ) =>
						this.setState( { url: event.target.value } )
					}
				/>
			);

		}

		return (
			<>
				<EmbedControls
					showEditButton={true}
					switchBackToURLInput={ this.switchBackToURLInput }
				/>
				<ServerSideRender
					block="betterembed/embed"
					attributes={ attributes }
					EmptyResponsePlaceholder={
						( { className } ) => {
							return (
								<EmbedPlaceholder
									icon={ icon }
									label={ 'Better Embed' }
									onSubmit={ this.setUrl }
									value={ url }
									cannotEmbed={ true }
									onChange={ ( event ) =>
										this.setState( { url: event.target.value } )
									}
									tryAgain={ this.setUrl }
									fallback={ () => fallback( url, this.props.onReplace ) }
								/>
							);
						}
					}
				/>
			</>
		);

	}

}

import { Component } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';

import EmbedControls from './embed-controls';
import EmbedPlaceholder from './embed-placeholder';
import icon from './icon';

export default class BetterEmbed extends Component {

	constructor() {
		super(...arguments);
		this.switchBackToURLInput = this.switchBackToURLInput.bind( this );
		this.setUrl = this.setUrl.bind( this );
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

	render() {
		const { attributes } = this.props;
		const { url, editingURL } = this.state;

		if ( ! attributes.url || editingURL ) {

			return (
				<div>
					<EmbedPlaceholder
						icon={ icon }
						label={ 'Better Embed' }
						onSubmit={ this.setUrl }
						value={ url }
						onChange={ ( event ) =>
							this.setState( { url: event.target.value } )
						}
					/>
				</div>
			);

		}

		return (
			<>
				<EmbedControls
					showEditButton={true}
					switchBackToURLInput={ this.switchBackToURLInput }
				/>
				<ServerSideRender
					block="betterembed/betterembed"
					attributes={ attributes }
				/>
			</>
		);

	}

}

/**
 * WordPress dependencies
 */
import { __, _x } from '@wordpress/i18n';
import { Button, Placeholder, ExternalLink } from '@wordpress/components';
import { BlockIcon } from '@wordpress/block-editor';

const EmbedPlaceholder = ( props ) => {
	const {
		icon,
		label,
		value,
		onSubmit,
		onChange,
		cannotEmbed,
		fallback,
		tryAgain,
	} = props;
	return (
		<Placeholder
			icon={ <BlockIcon icon={ icon } showColors /> }
			label={ label }
			className="wp-block-embed"
			instructions={ __(
				'Paste a link to the content you want to display on your site.',
				'betterembed'
			) }
		>
			<form onSubmit={ onSubmit }>
				<input
					type="url"
					value={ value || '' }
					className="components-placeholder__input"
					aria-label={ label }
					placeholder={ __(
						'Enter URL to embed here…',
						'betterembed'
					) }
					onChange={ onChange }
				/>
				<Button isPrimary type="submit">
					{ _x( 'Embed', 'button label', 'betterembed' ) }
				</Button>
			</form>
			<div className="components-placeholder__learn-more">
				<ExternalLink
					href={ __(
						'https://acolono.gitbook.io/betterembed/cms-integrations/wordpress-plugin'
					) }
				>
					{ __( 'Learn more about Better Embed', 'betterembed' ) }
				</ExternalLink>
			</div>
			{ cannotEmbed && (
				<div className="components-placeholder__error">
					<div className="components-placeholder__instructions">
						{ __(
							'Sorry, this content could not be embedded.',
							'betterembed'
						) }
					</div>
					<Button isSecondary onClick={ tryAgain }>
						{ _x( 'Try again', 'button label', 'betterembed' ) }
					</Button>{ ' ' }
					<Button isSecondary onClick={ fallback }>
						{ _x(
							'Convert to link',
							'button label',
							'betterembed'
						) }
					</Button>
				</div>
			) }
		</Placeholder>
	);
};

export default EmbedPlaceholder;

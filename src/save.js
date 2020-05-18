export default function save( { attributes } ) {

	const { url } = attributes;

	return (
		<div>
			{ url && (
				<a href={ url }>
					{ url }
				</a>
			) }
		</div>
	);
}

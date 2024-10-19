<h3>{{ __('Audio Export Options', 'pressbooks-network-analytics') }}</h3>
<table class="form-table pb-client-information" role="presentation">
	<tbody>
		<tr>
			<th scope="row">
				<label for="next-invoice-date">{{ __('Open AI API Key', 'pressbooks-audio-export') }}</label>
			</th>
			<td>
				<input type="text" id="openai_api_key" name="openai_api_key" value="{{ $openaiApiKey }}">
				<p class="description">{{ __('Enter your Open AI API key.', 'pressbooks-audio-export') }}</p>
			</td>
		</tr>
	</tbody>
</table>

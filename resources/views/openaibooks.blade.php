<h1>Export Audio with Open AI Settings</h1>
<form method="post" id="audio_export">
	{!! $nonce !!}
	<table class="form-table pb-client-information" role="presentation">
		<tbody>
			<tr>
				<th scope="row">
					<label for="next-invoice-date">{{ __('Open AI API Key', 'pressbooks-audio-export') }}</label>
				</th>
				<td>
					@if(! $openAiApiKeyNetwork)
						<input type="text" id="openai_api_key" name="openai_api_key" value="{{ $openaiApiKey }}">
						<p class="description">{{ __('Enter your Open AI API key.', 'pressbooks-audio-export') }}</p>
					@else
						<input type="text" id="openai_api_key" name="openai_api_key"  disabled>
						<p class="description">{{ __('This setting is managed at the network level and is already set.', 'pressbooks-audio-export') }}</p>
					@endif
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="book-limit">{{ __('Open AI Voice', 'pressbooks-audio-export') }}</label>
				</th>
				<td>
					<select id="openai_voice" name="openai_voice">
						<option value="alloy" @if($openAiVoice === 'alloy') selected @endif>
							{{ __('Alloy', 'pressbooks-audio-export') }}
						</option>
						<option value="echo" @if($openAiVoice === 'echo') selected @endif>
							{{ __('Echo', 'pressbooks-audio-export') }}
						</option>
						<option value="fable" @if($openAiVoice === 'fable') selected @endif>
							{{ __('Fable', 'pressbooks-audio-export') }}
						</option>
						<option value="onyx" @if($openAiVoice === 'onyx') selected @endif>
							{{ __('Onyx', 'pressbooks-audio-export') }}
						</option>
						<option value="nova" @if($openAiVoice === 'nova') selected @endif>
							{{ __('Nova', 'pressbooks-audio-export') }}
						</option>
						<option value="shimmer" @if($openAiVoice === 'shimmer') selected @endif>
							{{ __('Shimmer', 'pressbooks-audio-export') }}
						</option>
					</select>
					<p class="description">{{ __('Choose Open AI Voice for new audio exports', 'pressbooks-audio-export') }}</p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="{{ __('Save Changes', 'pressbooks-audio-export') }}">
	</p>
</form>

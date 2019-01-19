@extends('layouts.app-email') 

@section('content')


<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
	<tr>
		<td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
			<br><br>
			<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi {{ $candidate->name }},</p>
			<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
			Congratulations!!<br><br>
			Your CV has been shortlisted for the position '{{ $job->position }}'. 
			We need more clarity on your overall experience and capabilities. 
			Please go to the below link and give answers to few questions.
			This will enlight us how you are suitable with our job requirement.
			</p>
			<table border="0" cellpadding="0" cellspacing="0"
				class="btn btn-primary"
				style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
				<tbody>
					<tr>
						<td align="left"
							style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
							<table border="0" cellpadding="0" cellspacing="0"
								style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
								<tbody>
									<tr>
										<td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;">
											<a href="{{ $url }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;">Question set</a>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			
			<h3>Job description</h3>
			<?php echo $job->description ?>
			
			<h3>Job responsibility</h3>
			<?php echo $job->responsibilities ?>
			
			<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
				<h3>Instructions</h3>
				<ul>
					<li>This question set consists of few question related to the job description.</li>
					<li>Most of the questions will be multiple choice type.</li>
					<li>Few of them needs thorough answers, in that case you have to express your experience.</li>
				</ul>
			</p>
			<br>
			<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Good luck! Hope you will be valuable to us.</p>
		</td>
	</tr>
</table>


@endsection
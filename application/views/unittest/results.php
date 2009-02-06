
<h2>Results</h2>
<table class="uttable">
	<tr>
		<th>Files</th>
		<th>Tests</th>
		<th>Passed</th>
		<th>Failed</th>	
		<th>Time</th>
	</tr>
	<tr>
		<td class="center"><?=$results->num_files?></td>
		<td class="center"><?=$results->num_tests?></td>
		<td class="utpass center"><?=$results->num_passed?></td>
		<td <?= $results->num_failed ? 'class="utfail center"' : 'class="center"'?>><?=$results->num_failed?></td>
		<td class="center"><?= $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');?> seconds</td>
	</tr>
</table>

<hr/>

<h2>Details</h2>
		
<?php if( $results->tests ) {
		foreach( $results->tests as $test => $result ): ?>
		<table class="uttable">
		<caption><?= $test ?></caption>
		<tr>
			<th>Method</th><th>Result</th><th>Message</th><tr>
		<?php
		$count = 0;
		foreach( $results->tests[$test] as $method ): ?>
		 	<tr  <?=($count % 2 ? 'class="odd"' : "")?> > 
				<td width="30%"><?= $method["test"] ?></td> 
				<?php if($method["result"]) { ?>
					<td class="utpass">Passed: <?=$method['asserts']?> asserts
				<?php } else { ?>
					<td class="utfail">Failed
				<?php } ?>
			</td><td><?=$method["error"]?></td>
			</tr>
		<?php $count++;
			endforeach; ?>
		</table>
		<p/>
<?php endforeach; } ?>




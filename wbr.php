<?php

	class WebBookReader
	{
		private $version = '0.0.1';
		private $file_path;
		private $content;
		private $decompiled; // Does it really necessary?
		public $decompiled_book;

		public function __construct($id)
		{
			try {
				if (file_exists($id)) {
					$this->file_path = $id;
					$post_data = http_build_query(['access' => 'application']);
					$opts = [
						'http' => [
							'method'  => 'POST',
							'content' => $post_data
						]
					];
					$context = stream_context_create($opts);
					$this->content = file_get_contents($id, FALSE, $context);
					$this->process_text();
				} else {
					throw new WebBookReaderException($id);
				}
			} catch (WebBookReaderException $e) {
				echo $e;
				$this->__destruct();
			}
		}

		private function process_text()
		{
			$book = $this->content;
			$xml = simplexml_load_string($book);
			$json = json_encode($xml);
			$array = json_decode($json, TRUE);
			$this->_fetch_array($array);
		}

		private function _fetch_array($array = [], $part = '')
		{
			if ($part = '') {
				foreach ($array as $key => $value) {
					if ($key == 'wbr_file') {
						break; //Skipping header tag <wbr_file>
					}
					switch ($key) {
						case 'author':
							$this->decompiled['author'] = $value;
							break;
						case 'copyrights':
							$this->decompiled['copyrights'] = $value;
							break;
						case 'title':
							$this->decompiled['title'] = $value;
							break;
						case 'isbn':
							$this->decompiled['isbn'] = $value;
							break;
						case 'cover':
							$this->decompiled['cover'] = $value;
							break;
						case 'content':
							$this->_fetch_array($value, 'content');
							break;

					}

				}
			} elseif ($part = 'content') {

				foreach ($array as $key => $value) {
					//@TODO
				}
			}
		}

		public function render($type = 'html')
		{
			if ($type == 'html') {

			}
		}

		public function __destruct()
		{
		}
	}

	class WebBookReaderException extends Exception
	{
		public function __construct($file_name)
		{
			parent::$message = 'File ' . $file_name . ' not found or access denied (╯°□°)╯';
			parent::__construct(parent::$message, 0);
		}

		public function __toString()
		{
			return __CLASS__ . $this->message;
		}
	}
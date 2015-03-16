<?php
/**
 * Classe para pesquisas na Google
 * @author lspereira
 *
 */
class Google
{
	/**
	 * $term - termo de busca
	 * $time - a data para ser buscada, em JULIAN DAY
	 * $site - o site a ser buscado
	 * $rsz - o tamanho do resultado, SMALL, LARGE
	 * $api_version = 1.0
	 * $ref - a referencia, o site de qual a busca esta partindo
	 * $api_key - a nossa chave do API da google
	 * @var unknown_type
	 */
	public $term, $time, $site;
	public $rsz, $api_version, $ref, $api_key;
	private $ch, $gurl, $string = '';
	public $result;
	
	function __construct()
	{
		$this->time = unixtojd(time()) - 1; //1 = 1 dia. 7 = uma semana.. etc.
		$this->rsz = 'large'; //LARGE = 8 resultados
		$this->ref = 'http://suframa.gov.br/cba';
		$this->gurl = 'http://ajax.googleapis.com/ajax/services/search/web?'; //URL do API
		$this->turl = 'http://ajax.googleapis.com/ajax/services/language/translate?'; //translation API URL
		$this->api_version = '1.0';
		$this->site = '';
		$this->api_key = 'ABQIAAAAuG3VMAty_bucwg6ruXsPZxROHO9N7-vMq-U8a4sKaxNtCZh3UxSq11YrL_-d4BMytqE-o4vEh2bnOQ';
		
		$this->ch = curl_init(); //Inicia nosso objeto CURL
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1); //retorne um resultado, sim.
		curl_setopt($this->ch, CURLOPT_REFERER, $this->ref); //nossa referencia
	}
	
	function __deconstruct()
	{
		curl_close($this->ch);
	}
	
	function __toString()
	{
		return $this->string;
	}
	
	function Translate($term, $from, $to)
	{
		$term = stripslashes($term);
		$term = str_replace('"', '', $term);
		$url = $this->turl."v=$this->api_version&q=";
		
		$url .= str_replace(' ', '%20', $term);
		$url .= "&key=$this->api_key";
		$url .= "&langpair=$from%7C$to";
		
		//guardamos nosso formulado string de busca caso precisarmos para fazer debug
		$this->string = $url;
		//echo $this->string;
		curl_setopt($this->ch, CURLOPT_URL, $url);
		$this->result = curl_exec($this->ch);	
		//print_r($this->result);
		return $this->result;
	}
	
	/**
	 * Faz a busca
	 * @param $term termo de busca
	 * @return string resultado
	 */
	public function Search($term = null)
	{	
		if($term != null)
			$this->term = $term;
			
		if($this->term == null)
			return;
			
		$term = str_replace(' ', '%20', $term);
		
		$url = $this->gurl."v=$this->api_version&q=";
		
		if($this->site != null)
			$url.="site:$this->site%20";
		
		if($this->time != null)
			$url .= "daterange:$this->time-".unixtojd(time()).'%20';	
		
		$url .= $term;
		
		if(!empty($this->api_key))
			$url .= "&key=$this->api_key";
			
		$url .= "&rsz=$this->rsz";
		
		//guardamos nosso formulado string de busca caso precisarmos para fazer debug
		$this->string = $url;
		
		curl_setopt($this->ch, CURLOPT_URL, $url);
		$this->result = curl_exec($this->ch);		
		return $this->result;
	}

	
}

?>
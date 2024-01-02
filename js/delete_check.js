function check(){
  if(window.confirm('削除してよろしいですか？')){
    return true; 
  }
  else{
    window.alert('キャンセルされました');
    return false; 
  }
}
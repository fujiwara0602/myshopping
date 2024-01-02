function check(){
  if(window.confirm('購入を確定しますか？')){
    return true; 
  }
  else{
    window.alert('保留されました');
    return false; 
  }
}
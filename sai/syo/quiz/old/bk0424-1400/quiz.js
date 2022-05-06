function allcheck( tf ) {
  var ElementsCount = document.categories.elements.length; // チェックボックスの数
  for( i=0 ; i<ElementsCount ; i++ ) {
     document.categories.elements[i].checked = tf; // ON・OFFを切り替え
  }
}

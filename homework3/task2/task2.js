var str = "'JavaScri'pt' изначально создавался для того, чтобы сделать web-странички 'ж'ивыми'. Программы на этом " +
  "языке называются скриптами. В браузере они подключаются напрямую к HTML и, " +
  "как только загружается страничка – тут же выполняются.";



console.log(str.replace(/(\b\'\B|\B\'\b)/ig, '\"'));

//console.log(str.replace(/[?:\wа-яё]\'[^\wа-яё]/ig, '\"'));
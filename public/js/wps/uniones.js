let partial = function(fn) {
    let pastArgs = [...arguments].slice(1);
    return function() {
      let newArgs = [...arguments];
      return fn.apply(null, pastArgs.concat(newArgs));
    }
  };
  
  let extractRows = (tbody) => {
    var nodeList = tbody.querySelectorAll('tr');
    return [...nodeList].map((tr) => {
      tr.data = tr.innerText.toLowerCase();
      return tr;
    })
  };
  
  let updateVisibility = (text, row) => {
    row.style.display = row.data.indexOf(text) > -1 ? '': 'none';
  };
  
  let filterRows = ({elem, rows, errorElem}) => {
    let inputVal = elem.value.toLowerCase();
    rows.forEach(partial(updateVisibility, inputVal));
    errorElem.innerHTML = rows.filter(tr => tr.style.display !== 'none').length === 0 ?
      `<div>No hay criterios de búsqueda para el ${inputVal} ingresado</div>`: '';
    errorElem.style.display = '';
  };
  
  const tableId = '#tabla';
  let tbody = document.querySelector(`${tableId} tbody`);
  let errorElem = document.createElement('tr');
  tbody.appendChild(errorElem);
  
  let rows = extractRows(tbody);
  let inputElem = document.getElementById('grado_material');
  inputElem.onkeyup = partial(filterRows, {elem: inputElem , rows, errorElem});
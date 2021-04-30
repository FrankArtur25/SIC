function datenum(v, date1904) {
	if(date1904) v+=1462;
	var epoch = Date.parse(v);
	return (epoch - new Date(Date.UTC(1899, 11, 30))) / (24 * 60 * 60 * 1000);
}
 
function sheet_from_array_of_arrays(data, opts) {
	var ws = {};
	var range = {s: {c:10000000, r:10000000}, e: {c:0, r:0 }};
	for(var R = 0; R != data.length; ++R) {
		for(var C = 0; C != data[R].length; ++C) {
			if(range.s.r > R) range.s.r = R;
			if(range.s.c > C) range.s.c = C;
			if(range.e.r < R) range.e.r = R;
			if(range.e.c < C) range.e.c = C;
			var cell = {v: data[R][C] };
			if(cell.v == null) continue;
			var cell_ref = XLSX.utils.encode_cell({c:C,r:R});
			
			if(typeof cell.v === 'number') cell.t = 'n';
			else if(typeof cell.v === 'boolean') cell.t = 'b';
			else if(cell.v instanceof Date) {
				cell.t = 'n'; cell.z = XLSX.SSF._table[14];
				cell.v = datenum(cell.v);
			}
			else cell.t = 's';
			
			if(R == 0 || R==data.length-1){
				cell.s={
					fill:{
						fgColor:{ rgb: "07417a" }
                    },
                    font:{
						color:{rgb:"fafbfd"},
						sz:'11'
                    },
                    alignment:{
                        horizontal:"center"
					}
					
				}
			}

			if(R> 0 && R<data.length-1 && C>0){
				cell.s={
                    alignment:{
                        horizontal:"right"
					},
					font:{
						sz:"11"
					}
				}
            }

			// estilo 1
			if(opts==1 || opts==2){
				if(R == 1){
					cell.s={
						fill:{
							fgColor:{ rgb: "07417a" }
						},
						font:{
							color:{rgb:"fafbfd"},
							sz:'11'
						},
						alignment:{
							horizontal:"center"
						}
					}

				}
				
			}
            
			ws[cell_ref] = cell;
		}
	}
	if(range.s.c < 10000000) ws['!ref'] = XLSX.utils.encode_range(range);
	if(opts==1){
		ws["!merges"] = [
			{ s: { c: 2, r: 0 }, e: { c: 3, r: 0 } },
		];
	}
	if(opts==2){
		ws["!merges"] = [
			{ s: { c: 1, r: 0 }, e: { c: 5, r: 0 } },
			{ s: { c: 6, r: 0 }, e: { c: 10, r: 0 } },
		];
	}

	return ws;
}
 

function Workbook() {
	if(!(this instanceof Workbook)) return new Workbook();
	this.SheetNames = [];
	this.Sheets = {};
}
 

function s2ab(s) {
	var buf = new ArrayBuffer(s.length);
	var view = new Uint8Array(buf);
	for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
	return buf;
}


/////////////////////////////////////////////////////////////////////////////////////////

module.exports.exportar=function (data,titulo,titulo_hoja,tipo,paginas,data2,titulo_hoja2){
	/* original data */
	// var data = [[1,2,3,4,5],["Sample", "Sample", "Sample", "Sample",4125.55],["foo","bar","Hello","0.3"], ["baz", null, "qux"]]
	var wb = new Workbook();
	
	// hoja 1--------------------------------------------------------------------------
	var ws_name = "REPORTE "+titulo_hoja;
	var ws = sheet_from_array_of_arrays(data,tipo);
	 
	/* add worksheet to workbook */
	wb.SheetNames.push(ws_name);
	wb.Sheets[ws_name] = ws;
	var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});
	//----------------------------------------------------------------------------------

	// hoja2----------------------------------------------------------------------------
	if(paginas==2){
		var wss_name = "REPORTE "+titulo_hoja2;
		var wss = sheet_from_array_of_arrays(data2,tipo);
		wb.SheetNames.push(wss_name);
		wb.Sheets[wss_name] = wss;
		var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});
	}
	//-----------------------------------------------------------------------------------

	saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), titulo+".xlsx")
}



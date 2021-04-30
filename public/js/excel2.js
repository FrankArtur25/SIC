function datenum(v, date1904) {
	if(date1904) v+=1462;
	var epoch = Date.parse(v);
	return (epoch - new Date(Date.UTC(1899, 11, 30))) / (24 * 60 * 60 * 1000);
}

function sheet_from_array_of_arrays(data,cantidad, opts) {
	var ws = {};
	var range = {s: {c:10000000, r:10000000}, e: {c:0, r:0 }};
	for(var R = 0; R != data.length; ++R) {
		for(var C = 0; C != data[R].length; ++C) {
			if(range.s.r > R) range.s.r = R;
			if(range.s.c > C) range.s.c = C;
			if(range.e.r < R) range.e.r = R;
			if(range.e.c < C) range.e.c = C;
			if(range.e.c < C) range.e.c = C;
			var cell = {v: data[R][C] };
			//var cell2 = {v: data[data.length-(cantidad+6)][15] };
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
					/*fill:{
						fgColor:{ rgb: "07417a" }
                    },*/
                    font:{
                        color:{rgb:"fafbfd"},
                    },
                    alignment:{
                        horizontal:"center"
					},
					border:{
						top: { style: 'thin', color: { rgb: "07417a" } },
						bottom: { style: 'thin', color: { rgb: "07417a" } },
						left: { style: 'thin', color: { rgb: "07417a" } },
						right: { style: 'thin', color: "fafbfd" },
						/*top: { style: BORDER_STYLE, color: COLOR_SPEC },
						bottom: { style: BORDER_STYLE, color: COLOR_SPEC },*/
					},
				}
			}
			if(R>= 0 && R<=data.length-1 && C>=0){
				cell.s={
                    alignment:{
                        horizontal:"center"
					},
					border:{
						top: { style: 'thin', color: { rgb: "07417a" } },
						bottom: { style: 'thin', color: { rgb: "07417a" } },
						left: { style: 'thin', color: { rgb: "07417a" } },
						right: { style: 'thin', color: "fafbfd" },
					},
				}
            }

            
			ws[cell_ref] = cell;
		}
	}
	if(range.s.c < 10000000) ws['!ref'] = XLSX.utils.encode_range(range);
	ws["!merges"] = [
		{ s: { c: 0, r: 1 }, e: { c: 0, r: 2 } }, /* A1:B2 */
		{ s: { c: 1, r: 1 }, e: { c: 1, r: 2 } },
		{ s: { c: 2, r: 1 }, e: { c: 2, r: 2 } },
		{ s: { c: 3, r: 1 }, e: { c: 3, r: 2 } },
		{ s: { c: 4, r: 1 }, e: { c: 4, r: 2 } },
		{ s: { c: 5, r: 1 }, e: { c: 6, r: 1 } },
		{ s: { c: 7, r: 1 }, e: { c: 9, r: 1 } },
		{ s: { c: 10, r: 1 }, e: { c: 11, r: 1 } },
		{ s: { c: 12, r: 1 }, e: { c: 15, r: 1 } },

		{ s: { c: 0, r: data.length-(cantidad+3) }, e: { c: 0, r: data.length-(cantidad+2) } },
		{ s: { c: 1, r: data.length-(cantidad+3) }, e: { c: 1, r: data.length-(cantidad+2) } },
		{ s: { c: 2, r: data.length-(cantidad+3) }, e: { c: 2, r: data.length-(cantidad+2) } },
		{ s: { c: 3, r: data.length-(cantidad+3) }, e: { c: 3, r: data.length-(cantidad+2) } },
		{ s: { c: 4, r: data.length-(cantidad+3) }, e: { c: 4, r: data.length-(cantidad+2) } },
		{ s: { c: 5, r: data.length-(cantidad+3) }, e: { c: 6, r: data.length-(cantidad+3) } },
		{ s: { c: 7, r: data.length-(cantidad+3) }, e: { c: 9, r: data.length-(cantidad+3) } },
		{ s: { c: 10, r: data.length-(cantidad+3) }, e: { c: 11, r: data.length-(cantidad+3) } },
		{ s: { c: 12, r: data.length-(cantidad+3) }, e: { c: 15, r: data.length-(cantidad+3) } }
    ]
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

module.exports.exportar=function (data,cantidad,titulo,titulo_hoja){
	/* original data */
	// var data = [[1,2,3,4,5],["Sample", "Sample", "Sample", "Sample",4125.55],["foo","bar","Hello","0.3"], ["baz", null, "qux"]]
	var ws_name = "REPORTE "+titulo_hoja;
	 
	var wb = new Workbook(), ws = sheet_from_array_of_arrays(data,cantidad);
	 
	/* add worksheet to workbook */
	wb.SheetNames.push(ws_name);
	wb.Sheets[ws_name] = ws;
	var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});

	saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), titulo+".xlsx")
}
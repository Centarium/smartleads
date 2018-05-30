class Sort
{
    /**
     * @param {string} table
     */
    constructor(table)
    {
        this.tableID = table;
        this.table = $(table);

        this.state = {
            tableData : {
                titles : [],
                body : []
            },
        }

        this.createTitleData();
        this.createBodyData();

        this.handlers();

    }

    createTitleData()
    {
        let titles = this.table.find('thead tr th');

        titles.each((idx,target) => {
            let column_title = $(target).text(),
                column_type = $(target).data('type');

            this.state.tableData.titles.push({
                title:column_title,
                datatype : column_type
            })
        })

    }

    createBodyData()
    {
        var data_elem = {};
        let data = this.table.find('tbody tr');

        data.each((idx,target) => {

            let row = $(target).find('td');

            data_elem = {};

            row.each((rowid, rowtg) => {
                data_elem[rowid] = $(rowtg).text();
            });

            this.state.tableData.body.push(data_elem)

        });
    }

    tableSortHandler()
    {
        let titles = this.tableID + ' thead tr th';

        $(document).on('click',titles,{},() =>
        {
            let target = $(event.target);
            var index = target.context.cellIndex;
            let column_type = this.state.tableData.titles[index].datatype,
                callback = this.getCompareFunction(column_type);

            this.state.tableData.body.sort(callback.bind(null,index));
            this.render();
        })
    }

    getCompareFunction(columnType)
    {
        switch(columnType)
        {
            case 'integer' : {return this.compareNumbers;}
            case 'date' : {return this.compareDate;}
            case 'text' : {return this.compareString;}
            case 'decimal' : {return this.compareFloat;}
            default : {return this.compareString }
        }
    }

    compareNumbers(index,a,b)
    {
        return parseInt(a[index]) - parseInt(b[index]);
    }

    compareFloat(index,a,b)
    {
        return parseFloat(a[index]) - parseFloat(b[index]);
    }

    compareString(index,a,b)
    {
        if (a[index] > b[index]) {
            return 1;
        }
        if (a[index] < b[index]) {
            return -1;
        }
        return 0;
    }

    compareDate(index,a,b)
    {
        let d1 = a[index].split('.');
        let d2 = b[index].split('.');

        return new Date(d1[2],d1[1],d1[0]) - new Date(d2[2],d2[1],d2[0]);
    }

    handlers()
    {
        this.tableSortHandler();
    }

    /**
     * Need JSX!!!
     */
    render()
    {
        let body =  this.tableID + ' tbody';

        let new_body = '',
            len = this.state.tableData.body.length;

        for (let i=0;i<len;i++)
        {
            let row = this.state.tableData.body[i];

            new_body += '<tr>';

            for(let item in row)
            {
                new_body += '<td>'+row[item]+'</td>';
            }

            new_body += '</tr>';
        }

        $(body).html(new_body);
    }


}

var TableSort = new Sort('#smartLeadsEmployers');
const columnTypes = [
    {
        name: "Numbers",
        types: ["TINYINT", "SMALLINT", "MEDIUMINT", "INT", "BIGINT", "DECIMAL", "FLOAT", "DOUBLE", "BOOLEAN"]
    },
    {
        name: "Dates",
        types: ["DATE", "DATETIME", "TIMESTAMP", "TIME", "YEAR"]
    },
    {
        name: "Strings",
        types: ["CHAR", "VARCHAR", "TINYTEXT", "TEXT", "MEDIUMTEXT", "LONGTEXT"]
    },
    {
        name: "JSON",
        types: ["JSON"]
    }
];

function columnTypeOptions(selectedType = null) {
    const optionGroups = [];
    columnTypes.forEach((group) => {
        const optionGroup = document.createElement('optgroup');
        optionGroup.label = group.name;
        group.types.forEach((type) => {
            const option = document.createElement('option');
            option.value = type;    
            option.text = type;
            if (selectedType && type.toLowerCase() === selectedType.toLowerCase()) {
                option.setAttribute('selected', true);
            }
            optionGroup.appendChild(option);
        });
        optionGroups.push(optionGroup);
    });
    return optionGroups;
}

const columnDefaults = [
    {
        name: "None",
        value: "none"
    },
    {
        name: "Specified",
        value: "specified"
    },
    {
        name: "Null",
        value: "NULL"
    },
    {
        name: "Current Timestamp",
        value: "CURRENT_TIMESTAMP"
    }
];

function columnDefaultOptions(column, columnCounter) {
    const selectDiv = document.createElement('div');
    selectDiv.className = 'gap-2 d-flex';
    const select = document.createElement('select');
    select.classList.add('form-select');
    select.setAttribute('data-column-index', columnCounter);
    select.setAttribute('data-column-field', 'default');
    columnDefaults.forEach((option) => {
        const optionElement = document.createElement('option');
        optionElement.value = option.value;
        optionElement.text = option.name;
        select.appendChild(optionElement);
    });

    if (column.Default === null && column.Null === 'YES') {
        select.querySelector('option[value="NULL"]').setAttribute('selected', true);
    } else if (column.Default === null) {
        select.querySelector('option[value="none"]').setAttribute('selected', true);
    } else if (column.Default === 'CURRENT_TIMESTAMP') {
        select.querySelector('option[value="CURRENT_TIMESTAMP"]').setAttribute('selected', true);
    } else {
        select.querySelector(`option[value="specified"]`).setAttribute('selected', true);
        const input = document.createElement('input');
        input.classList.add('form-control');
        input.setAttribute('data-column-index', columnCounter);
        input.setAttribute('data-column-field', 'default');
        input.setAttribute('placeholder', 'Default value');
        input.defaultValue = column.Default;
        selectDiv.appendChild(input);
    }
    selectDiv.appendChild(select);
    // Reverse the order of the elements
    selectDiv.append(...Array.from(selectDiv.childNodes).reverse());
    return selectDiv;
}

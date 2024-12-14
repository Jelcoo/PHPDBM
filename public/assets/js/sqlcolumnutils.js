const columnTypesSpecial = [
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

function columnTypeOptions() {
    const optionGroups = [];
    columnTypesSpecial.forEach((group) => {
        const optionGroup = document.createElement('optgroup');
        optionGroup.label = group.name;
        group.types.forEach((type) => {
            const option = document.createElement('option');
            option.value = type;    
            option.text = type;
            optionGroup.appendChild(option);
        });
        optionGroups.push(optionGroup);
    });
    return optionGroups;
}
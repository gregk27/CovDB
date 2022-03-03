# Custom Python script to generate model classes from SQL CREATE TABLE statements
# Designed to save me a bit of time making the classes (plus it killed an hour)

# Designed to work with my SQL script, will not work on all (most?) scripts

import re

types = {
    "INTEGER": "int",
    "DOUBLE": "double",
    "FLOAT": "float",
    "CHAR": "string",
    "VARCHAR": "string",
    "DATE": "string",
    "DATETIME": "string",
    "ENUM": "string"
}

def buildStr(name, cols):
    outstr = "<?php\n\n"
    outstr += f"/**\n * Class representing a {name}\n * Automatically generated from SQL script by modelgen.py\n */\n"
    outstr += "class "+name+" {\n"
    for col in cols:
        outstr += f"\tpublic {col[1]} ${col[0]};\n"
    outstr += "\n\tpublic function __construct("
    for col in cols:
        outstr += f"${col[0]}, "
    outstr = outstr.rstrip(',')
    outstr += ") {\n"
    for col in cols:
        outstr += f"\t\t$this->{col[0]} = ${col[0]};\n"
    outstr += "\t}\n\n\tpublic function toAssoc() {\n\t\treturn get_object_vars($this);\n"
    outstr += "\t}\n\n\tpublic static function fromAssoc($assoc) {\n"
    outstr += f"\t\treturn new {name}(\n"
    for col in cols:
        outstr += f"\t\t\t$assoc[\"{col[0]}\"],\n"
    outstr = outstr.rstrip(',')
    outstr += "\t\t);\n\t}\n}"
    return outstr


filename = input("Enter sql file to parse: ")
file = open(filename, 'r')

while True:
    name = None
    cols = []
    while True:
        line = file.readline()
        if(name is None):
            match = re.search("CREATE\\s+TABLE\\s+(\\w+)", line)
            if(match is None):
                break
            name = match.group(1)
            continue
        match = re.search("^\\s+(\\w+)\\s+(\\w+)", line)
        if(match is not None and match.group(2).upper() in types):
            cols.append([match.group(1), types[match.group(2).upper()]])
        if(line.strip().endswith(";")):
            break
    if(name is not None):
        open(name+".php", 'w').write(buildStr(name, cols))

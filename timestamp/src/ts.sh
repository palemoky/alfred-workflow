!#/bin/bash

# Author: Palemoky

Output()
{
    echo $(printf '{"items":[{"title":"%s","subtitle":"%s","arg":"%s"}]} \n' "$title" "$subtitle" "$title")
    exit 0
}

arg=$(echo $1 | xargs)

# Current timestamp
if [ -z $arg ]; then
    title=$(date +%s)
    subtitle=$(date "+%Y-%m-%d %H:%M:%S")
    Output
fi

# Convert timestamp to datetime
if [ $arg -gt 0 ] 2>/dev/null ;then 
    tzOffset=$(date +%z)
    jetLag=${tzOffset: 0 :3}
    title=$(date -v${jetLag}H -ur $arg "+%F %T")
    subtitle=$arg
    Output
fi

# Convert date to timestamp
if [[ $arg =~ ^[0-9]{4}-[0-9]{2}-[0-9]{2}$ ]]; then
    title=$(date -jf "%F" "${arg}" "+%s")
    subtitle=$arg
    Output
elif [[ $arg =~ ^[0-9]{4}-[0-9]{2}-[0-9]{2}[[:space:]][0-9]{2}:[0-9]{2}:[0-9]{2}$ ]]; then
    title=$(date -jf "%F %T" "${arg}" "+%s")
    subtitle=$arg
    Output
else
    title="Invalid date"
    subtitle=""
    Output
fi

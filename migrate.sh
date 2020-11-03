#!/usr/bin/env bash
docker run -ti --rm -v $PWD/db:/flyway/db -v $PWD/db/sql:/flyway/sql -v $PWD/db/conf:/flyway/conf --user root flyway/flyway ${1:-migrate} -n

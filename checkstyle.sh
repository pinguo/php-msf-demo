#!/bin/bash

server=`pwd`
checkstyle --server=$server --target=app $@

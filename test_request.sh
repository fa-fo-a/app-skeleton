#!/bin/bash

curl -X POST http://nginx/ \
     -H "Content-Type: application/json" \
     -d '{
           "a": 3,
           "b": 5
         }'

cmake_minimum_required(VERSION 3.13)
project(SensorSystem)

set(CMAKE_CXX_STANDARD 17)
set(CMAKE_RUNTIME_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/bin)

add_executable(SensorSystem libs/simpleini/SimpleIni.h src/main.cpp src/BME280.cpp src/BME280.h src/TrimmingParameters.cpp src/TrimmingParameters.h src/Repository/BME280Repository.cpp src/Repository/BME280Repository.h src/Repository/BME280Repository.cpp src/Repository/BME280Repository.h src/Exception/BadMethodCallException.cpp src/Exception/BadMethodCallException.h src/Config/MariaDBConfig.cpp src/Config/MariaDBConfig.h)

list(APPEND CMAKE_MODULE_PATH ${CMAKE_CURRENT_LIST_DIR}/cmake)
find_package(MariaDBClient REQUIRED)

include_directories(${MariaDBClient_INCLUDE_DIR} /usr/local/include)

target_link_libraries(SensorSystem stdc++fs wiringPi ${MariaDBClient_LIBRARY} mariacpp)

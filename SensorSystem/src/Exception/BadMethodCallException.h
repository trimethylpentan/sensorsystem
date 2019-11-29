#pragma once

#include <exception>
#include <string>
namespace SensorSystem {
    class BadMethodCallException : public std::exception {
    public:
        explicit BadMethodCallException(std::string exceptionMessage);
        std::string what();

    private:
        std::string message;
    };
}

package utils

func Map[T any, U any](data []T, f func(T) U) []U {
	result := make([]U, len(data))
	for i, v := range data {
		result[i] = f(v)
	}

	return result
}

func Reduce[T any, U any](data []T, initial U, f func(U, T) U) U {
	acc := initial
	for _, v := range data {
		acc = f(acc, v)
	}

	return acc
}

func Sum[T any](data []T, f func(T) int) int {
	return Reduce(data, 0, func(acc int, curr T) int {
		return acc + f(curr)
	})
}

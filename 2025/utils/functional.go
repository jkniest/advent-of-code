package utils

func Map[T any, U any](data []T, f func(T, int) U) []U {
	result := make([]U, len(data))
	for i, v := range data {
		result[i] = f(v, i)
	}

	return result
}

type FlatMapKeyRes[U any] struct {
	Item U
	Key  int
}

func FlatMapKey[T any, U any](data []T, f func(T, int) []FlatMapKeyRes[U]) map[int]U {
	result := make(map[int]U)
	for i, v := range data {
		res := f(v, i)
		for _, r := range res {
			result[r.Key] = r.Item
		}
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

func MapToSlice[T any, U comparable](items map[U]T) []T {
	result := make([]T, len(items))
	for _, item := range items {
		result = append(result, item)
	}

	return result
}

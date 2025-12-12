package utils

type Collection[T any] struct {
	Value []T
}

func NewCollection[T any](initialData []T) Collection[T] {
	return Collection[T]{
		Value: initialData,
	}
}

func (c Collection[T]) Sum(f func(T) int) int {
	return Sum(c.Value, f)
}

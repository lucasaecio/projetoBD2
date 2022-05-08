## ProjetoBD2 - BACKEND - LARAVEL

PROCEDURE:
````
CREATE PROCEDURE [dbo].[RetornaProdutos]
(
@SupplierID INT
)
as
BEGIN
	select c.CompanyName, p.ProductName, SUM(OD.Quantity) as Quantity
	from Orders O JOIN [Order Details] OD 
	on O.OrderID = OD.OrderID JOIN Customers c
	on c.CustomerID = O.CustomerID JOIN Products p
	on OD.ProductID = p.ProductID JOIN Suppliers s
	on p.SupplierID = s.SupplierID
	where s.SupplierID = @SupplierID
	GROUP BY c.CompanyName, p.ProductName
END;
GO
````

TRIGGER:
````
CREATE TRIGGER impedeInsertForaEstoque ON [Order Details]
FOR INSERT, UPDATE AS 
BEGIN 
  
    IF EXISTS(
			Select i.Quantity from inserted as i
			JOIN Products as p on p.ProductID = i.ProductID
			WHERE i.Quantity > p.UnitsInStock
		)
		BEGIN 
			DELETE FROM [Order Details] WHERE [Order Details].OrderID =(Select i.OrderID from inserted as i) 
			DELETE FROM Orders WHERE Orders.OrderID =(Select i.OrderID from inserted as i) 
			
			RAISERROR ('Inserção não permitida, Quantidade excede estoque do produto' ,10,1)
			print 'Inserção não permitida' 
			ROLLBACK TRANSACTION; 
		END 
	ELSE
		BEGIN
			UPDATE Products 
			SET Products.UnitsInStock = (Select (p.UnitsInStock - i.Quantity) from inserted as i JOIN Products as p on p.ProductID = i.ProductID)
			  WHERE Products.ProductID =(Select i.ProductID from inserted as i JOIN Products as p on p.ProductID = i.ProductID)
		END
END;
GO
````
